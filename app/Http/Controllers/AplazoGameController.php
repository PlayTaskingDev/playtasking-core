<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateAplazoPaymentRequest;
use App\Models\AplazoGame;
use App\Models\AplazoLoan;
use App\Models\AwardCode;
use App\Models\Setting;
use App\Models\UserInteraction;
use Illuminate\Http\Request;
use App\Traits\CampaignsTrait;
use App\Traits\CheckParticipationTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AplazoGameController extends Controller
{
    use CampaignsTrait, UploadImageTrait, CheckParticipationTrait;

    public function index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
    }

    public function show($slug, Request $request)
    {
        $today = Carbon::now()->toDateTimeString();
        $aplazo_game = AplazoGame::with('campaign')
            ->where(
            [
                ['slug', $slug],
                ['init_date','<',$today],
                ['end_date','>',$today]
            ])->firstOrFail();

        // Check if user has been participated and won
        /* $has_participated = $this->check_participation($model_id = $aplazo_game->id,$model_type = 'App\Models\AplazoGame',$user_id = $request->user()->id,$hit = true);
        if (!is_null($has_participated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $aplazo_game->award]));
        } */

        // Check if remaining coupons
        $award_code = AwardCode::where([['award_id',$aplazo_game->award->id],['active',false]])->count();
        if ($award_code <= 0) {
            return redirect()->route('game.out_of_coupons', ['tenant' => tenant('id')]);
        }

        $campaign_games = $this->has_content_type($aplazo_game->campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($aplazo_game->campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($aplazo_game->campaign->id, 'coupons');

        return view('dashboard.aplazo.show', [
            'aplazo_game'       => $aplazo_game,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function gateway(AplazoGame $aplazoGame, ValidateAplazoPaymentRequest $request)
    {
        // Check if user has been participated and won
        /* $has_participated = $this->check_participation($model_id = $aplazoGame->id,$model_type = 'App\Models\AplazoGame',$user_id = $request->user()->id,$hit = true);
        if (!is_null($has_participated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $aplazoGame->award]));
        } */

        // Begin Aplazo procedure
        $setting = Setting::first();
        try {
            $token_response = Http::post($setting->aplazo_endpoint . '/api/auth', [
                'apiToken'      => $setting->aplazo_api_token,
                'merchantId'    => $setting->aplazo_merchant_id
            ]);
            $token = str_replace('Bearer ', '', $token_response->json('Authorization'));
            $cart_id = Str::uuid()->toString();
        } catch (\Throwable $th) {
            throw $th;
        }

        $body = [
            'totalPrice'    => $aplazoGame->price,
            'shopId'        => env('APP_NAME'),
            'cartId'        => $cart_id,
            'successUrl'    => route('aplazo.payment', ['tenant' => tenant('id')]) . '?order=' . $cart_id,
            'errorUrl'      => route('campaign.splash', ['tenant' => tenant('id')]),
            'cartUrl'       => route('aplazo.show', ['tenant' => tenant('id'), 'slug' => $aplazoGame->slug]),
            'webHookUrl'    => route('aplazo.webhook', ['tenant' => tenant('id')]),
            'buyer'         => [
                'addressLine'   => '',
                'email'         => $request->user()->email,
                'firstName'     => $request->user()->name,
                'lastName'      => '',
                'loan_id'       => $aplazoGame->id,
                'phone'         => '',
                'postalCode'    => ''
            ],
            'products'  => [
                [
                    'id'            => $aplazoGame->slug,
                    'count'         => 1,
                    'description'   => $aplazoGame->product_description,
                    'title'         => $aplazoGame->product_name,
                    'imageUrl'      => $aplazoGame->featured_image,
                    'price'         => $aplazoGame->price,
                ]
            ],
            'discount'  => [
                'price' => 0,
                'title' => ''
            ],
            'shipping'  => [
                'price' => 0,
                'title' => ''
            ],
            'taxes' => [
                'price' => 0,
                'title' => 'IVA'
            ]
        ];

        try {
            $response = Http::withToken($token)->post($setting->aplazo_endpoint . '/api/loan', $body);
            
            $url = $response->json('url');
            $token = $response->json('loanToken');
            $loan_id = $response->json('loanId');

            DB::table('aplazo_loans')->insert([
                [
                    'id'                => Str::uuid(),
                    'url'               => $url,
                    'loan_id'           => $loan_id,
                    'cart_id'           => $cart_id,
                    'token'             => $token,
                    'status'            => 'NO CONFIRMADO',
                    'aplazo_game_id'    => $aplazoGame->id,
                    'user_id'           => $request->user()->id,
                    'created_at'        => Carbon::now()->toDateTimeString(),
                    'updated_at'        => Carbon::now()->toDateTimeString(),
                ]
            ]);

            return redirect()->away($url);
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function payment(Request $request)
    {
        $cart_id = $request->query('order');

        $loan = AplazoLoan::where('cart_id', $cart_id)->first();
        $loan->status = 'ACTIVO';
        $loan->save();

        $aplazoGame = AplazoGame::with('award')->find($loan->aplazo_game->id);

        // Give the prize
        DB::table('award_user')->insert([
            'model_id'      => $aplazoGame->id,
            'award_id'     => $aplazoGame->award->id,
            'user_id'       => $request->user()->id,
            'model_type'    => \App\Models\AplazoGame::class,
            'hit'           => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        $user_interaction = UserInteraction::create([
            'model_id' => $aplazoGame->id,
            'model_title' => $aplazoGame->title,
            'user_id' => Auth::user()->id,
            'hit' => true,
            'hit_created_at' => Carbon::now(),
            'hit_updated_at' => Carbon::now(),
        ]);

        $award_code = AwardCode::where([['award_id',$aplazoGame->award->id],['active',false]])->first();

        if (!is_null($award_code)) {
            $award_code->active = true;
            $award_code->user_id = $request->user()->id;
            $award_code->save();

            $user_interaction->code = $award_code->code;
            $user_interaction->save();
        } else {
            return redirect()->route('game.out_of_coupons', ['tenant' => tenant('id')]);
        }

        return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $aplazoGame->award]));
    }

    public function webhook(Request $request)
    {

    }
}
