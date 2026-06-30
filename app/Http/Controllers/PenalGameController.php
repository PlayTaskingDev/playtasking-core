<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\CampaignsTrait;
use App\Traits\CheckParticipationTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\AwardCode;
use App\Models\PenalGame;
use App\Models\UserInteraction;

class PenalGameController extends Controller
{
    use CheckParticipationTrait, CampaignsTrait;

    public function index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);

    }

    public function show($slug)
    {
     
        $penal_game = $this->get_penal_game($slug);
        
        $puzzle_settings = [
            'a' => $penal_game->points_per_pipe,
            'c' => $penal_game->max_points,
            'e' => __('You Win!'),
            'f' => route('penal_game.complete', ['tenant' => tenant('id')]),
            'g' => $this->signature_hash($penal_game->id.$slug.$penal_game->award->id), //$penal_game->id,
            'h' => route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $penal_game->award]),
            'i' => route('game.start', ['tenant' => tenant('id')]),
            'j' => $slug
        ];

        
        // Check if user has been participated
        $has_paticipated = $this->check_participation($model_id = $penal_game->id,$model_type = 'App\Models\PenalGame',$user_id = Auth::user()->id);

        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $penal_game->award]));
        }

        $campaign_games = $this->has_content_type($penal_game->campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($penal_game->campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($penal_game->campaign->id, 'coupons');

        return view('dashboard.penal_games.show', [
            'penal_game'        => $penal_game,
            'puzzle_settings'   => json_encode($puzzle_settings),
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function get_penal_game( $slug){

        $today = Carbon::now()->toDateTimeString();
        return PenalGame::with('campaign')
            ->where(
            [
                ['slug', $slug],
                ['init_date','<',$today],
                ['end_date','>',$today]
            ])->firstOrFail();
        
    }
    public function penal_game_complete(Request $request)
    {
        $rules = [
            'data' => ['required'],
            'slug' => ['required']
        ];
        $validator = validator($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 'error'], 422);
        }

        $data = $validator->validated();
        $penal_game_data = $this->get_penal_game($data['slug']);

        // Check the signature to validate corrrect game
        if (!hash_equals($this->signature_hash($penal_game_data->id.$data['slug'].$penal_game_data->award->id), $data['data']) ){
            return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
        }
        $penal_game = PenalGame::with('award')->findOrFail($penal_game_data->id);

        // Check if user is out of time
        // $is_out_of_time = $this->out_of_time_validation(session('game_start'), $penal_game->seconds);
        // if ($is_out_of_time) {
        //     return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
        // }

        // Check if user has been participated and won
        $has_paticipated = $this->check_participation($model_id = $penal_game->id,$model_type = 'App\Models\PenalGame',$user_id = Auth::user()->id,$hit = true);
        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $penal_game->award]));
        }
        // Attach user to quiz with hit
        $query = DB::table('award_user')->insert([
            'model_id'      => $penal_game_data->id,
            'award_id'     => $penal_game->award->id,
            'user_id'       => Auth::user()->id,
            'model_type'    => 'App\Models\PenalGame',
            'hit'           => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        $user_interaction = UserInteraction::create([
            'model_id' => $penal_game->id,
            'model_title' => $penal_game->title,
            'user_id' => Auth::user()->id,
            'hit' => true,
            'hit_created_at' => session('game_start'),
            'hit_updated_at' => Carbon::now()->format('Y-m-d H:i:s.u'),
        ]);

        $award_code = AwardCode::where([['award_id',$penal_game->award->id],['active',false]])->first();

        if (!is_null($award_code)) {
            $award_code->active = true;
            $award_code->user_id = Auth::user()->id;
            $award_code->save();

            $user_interaction->code = $award_code->code;
            $user_interaction->save();
            session()->forget('game_start');
        } else {
            return redirect()->route('game.out_of_coupons', ['tenant' => tenant('id')]);
        }
        
        if ($query) {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'error'], 400);
        }
    }

    public function get_ogjects_images($objects){
        $arObjectImages = [];
        foreach($objects as $obj){
            $arObjectImages[] = $obj->object_image;
        }
        return $arObjectImages;
    }

}
