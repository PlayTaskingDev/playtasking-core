<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\CampaignsTrait;
use App\Traits\CheckParticipationTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\AwardCode;
use App\Models\SmashGame;
use App\Models\UserInteraction;

class SmashGameController extends Controller
{
    use CheckParticipationTrait, CampaignsTrait;

    public function index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);

    }

    public function show($slug)
    {
     
        $smash_game = $this->get_smash_game($slug);
        
        $puzzle_settings = [
            'a' => $smash_game->points_per_object,
            'b' => $smash_game->seconds,
            'c' => $smash_game->max_points,
            'd' => $this->get_ogjects_images($smash_game->smash_objects),
            'e' => __('You Win!'),
            'f' => route('smash_game.complete', ['tenant' => tenant('id')]),
            'g' => $this->signature_hash($smash_game->id.$slug.$smash_game->award->id), //$smash_game->id,
            'h' => route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $smash_game->award]),
            'i' => route('game.start', ['tenant' => tenant('id')]),
            'j' => $slug
        ];

        
        // Check if user has been participated
        $has_paticipated = $this->check_participation($model_id = $smash_game->id,$model_type = 'App\Models\SmashGame',$user_id = Auth::user()->id);

        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $smash_game->award]));
        }

        $campaign_games = $this->has_content_type($smash_game->campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($smash_game->campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($smash_game->campaign->id, 'coupons');

        return view('dashboard.smash_games.show', [
            'smash_game'        => $smash_game,
            'puzzle_settings'   => json_encode($puzzle_settings),
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function get_smash_game( $slug){

        $today = Carbon::now()->toDateTimeString();
        return SmashGame::with('campaign','smash_objects')
            ->where(
            [
                ['slug', $slug],
                ['init_date','<',$today],
                ['end_date','>',$today]
            ])->firstOrFail();
        
    }
    public function smash_game_complete(Request $request)
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
        $smash_game_data = $this->get_smash_game($data['slug']);

        // Check the signature to validate corrrect game
        if (!hash_equals($this->signature_hash($smash_game_data->id.$data['slug'].$smash_game_data->award->id), $data['data']) ){
            return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
        }
        $smash_game = SmashGame::with('award')->findOrFail($smash_game_data->id);

        // Check if user is out of time
        $is_out_of_time = $this->out_of_time_validation(session('game_start'), $smash_game->seconds);
        if ($is_out_of_time) {
            session()->forget('game_start');
            session()->forget('game_duration');
            return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
        }

        // Check if user has been participated and won
        $has_paticipated = $this->check_participation($model_id = $smash_game->id,$model_type = 'App\Models\SmashGame',$user_id = Auth::user()->id,$hit = true);
        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $smash_game->award]));
        }
        // Attach user to quiz with hit
        $query = DB::table('award_user')->insert([
            'model_id'      => $smash_game_data->id,
            'award_id'     => $smash_game->award->id,
            'user_id'       => Auth::user()->id,
            'model_type'    => 'App\Models\SmashGame',
            'hit'           => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        $user_interaction = UserInteraction::create([
            'model_id' => $smash_game->id,
            'model_title' => $smash_game->title,
            'user_id' => Auth::user()->id,
            'hit' => true,
            'hit_created_at' => session('game_start'),
            'hit_updated_at' => Carbon::now()->format('Y-m-d H:i:s.u'),
        ]);

        $award_code = AwardCode::where([['award_id',$smash_game->award->id],['active',false]])->first();

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
