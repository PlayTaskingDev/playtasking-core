<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\CampaignsTrait;
use App\Traits\CheckParticipationTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\AwardCode;
use App\Models\FlappyGame;
use App\Models\UserInteraction;

class FlappyGameController extends Controller
{
    use CheckParticipationTrait, CampaignsTrait;

    public function index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);

    }

    public function show($slug)
    {
     
        $flappy_game = $this->get_flappy_game($slug);
        
        $puzzle_settings = [
            'a' => $flappy_game->points_per_pipe,
            'c' => $flappy_game->max_points,
            'e' => __('You Win!'),
            'f' => route('flappy_game.complete', ['tenant' => tenant('id')]),
            'g' => $this->signature_hash($flappy_game->id.$slug.$flappy_game->award->id), //$flappy_game->id,
            'h' => route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $flappy_game->award]),
            'i' => route('game.start', ['tenant' => tenant('id')]),
            'j' => $slug
        ];

        
        // Check if user has been participated
        $has_paticipated = $this->check_participation($model_id = $flappy_game->id,$model_type = 'App\Models\FlappyGame',$user_id = Auth::user()->id);

        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $flappy_game->award]));
        }

        $campaign_games = $this->has_content_type($flappy_game->campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($flappy_game->campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($flappy_game->campaign->id, 'coupons');

        return view('dashboard.flappy_games.show', [
            'flappy_game'        => $flappy_game,
            'puzzle_settings'   => json_encode($puzzle_settings),
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function get_flappy_game( $slug){

        $today = Carbon::now()->toDateTimeString();
        return FlappyGame::with('campaign')
            ->where(
            [
                ['slug', $slug],
                ['init_date','<',$today],
                ['end_date','>',$today]
            ])->firstOrFail();
        
    }
    public function flappy_game_complete(Request $request)
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
        $flappy_game_data = $this->get_flappy_game($data['slug']);

        // Check the signature to validate corrrect game
        if (!hash_equals($this->signature_hash($flappy_game_data->id.$data['slug'].$flappy_game_data->award->id), $data['data']) ){
            return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
        }
        $flappy_game = FlappyGame::with('award')->findOrFail($flappy_game_data->id);

        // Check if user is out of time
        // $is_out_of_time = $this->out_of_time_validation(session('game_start'), $flappy_game->seconds);
        // if ($is_out_of_time) {
        //     return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
        // }

        // Check if user has been participated and won
        $has_paticipated = $this->check_participation($model_id = $flappy_game->id,$model_type = 'App\Models\FlappyGame',$user_id = Auth::user()->id,$hit = true);
        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $flappy_game->award]));
        }
        // Attach user to quiz with hit
        $query = DB::table('award_user')->insert([
            'model_id'      => $flappy_game_data->id,
            'award_id'     => $flappy_game->award->id,
            'user_id'       => Auth::user()->id,
            'model_type'    => 'App\Models\FlappyGame',
            'hit'           => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        $user_interaction = UserInteraction::create([
            'model_id' => $flappy_game->id,
            'model_title' => $flappy_game->title,
            'user_id' => Auth::user()->id,
            'hit' => true,
            'hit_created_at' => session('game_start'),
            'hit_updated_at' => Carbon::now()->format('Y-m-d H:i:s.u'),
        ]);

        $award_code = AwardCode::where([['award_id',$flappy_game->award->id],['active',false]])->first();

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
