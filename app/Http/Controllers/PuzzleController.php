<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\CampaignsTrait;
use App\Traits\CheckParticipationTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\AwardCode;
use App\Models\Puzzle;
use App\Models\UserInteraction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PuzzleController extends Controller
{
    use CheckParticipationTrait, CampaignsTrait;

    public function puzzle_index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);

    }

    public function puzzle_show($slug)
    {
        $today = Carbon::now()->toDateTimeString();
        $puzzle = Puzzle::with('campaign')
            ->where(
            [
                ['slug', $slug],
                ['init_date','<',$today],
                ['end_date','>',$today]
            ])->firstOrFail();

        // Check if user has been participated
        $has_paticipated = $this->check_participation($model_id = $puzzle->id,$model_type = 'App\Models\Puzzle',$user_id = Auth::user()->id);

        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $puzzle->award]));
        }

        $puzzle_settings = [
            'a' => $puzzle->seconds,
            'b' => $puzzle->id,
            'c' => $puzzle->puzzle_image,
            'd' => $puzzle->pieces,
            'e' => route('game.start', ['tenant' => tenant('id')]),
            'f' => route('puzzle.complete', ['tenant' => tenant('id')]),
            'g' => route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $puzzle->award]),
            'h' => $slug
        ];
        
    

        $campaign_games = $this->has_content_type($puzzle->campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($puzzle->campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($puzzle->campaign->id, 'coupons');


        return view('dashboard.puzzles.show', [
            'puzzle'            => $puzzle,
            'puzzle_settings'   => json_encode($puzzle_settings),
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function puzzle_complete(Request $request)
    {
        $rules = [
            'data' => ['required','exists:puzzles,id']
        ];

        $validator = validator($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 'error'], 422);
        }

        $data = $validator->validated();

        $puzzle = Puzzle::with('award')->findOrFail($data['data']);

        // Check if user is out of time
        $is_out_of_time = $this->out_of_time_validation(session('game_start'), $puzzle->seconds);
        if ($is_out_of_time) {
            return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
        }

        // Check if user has been participated and won
        $has_paticipated = $this->check_participation($model_id = $puzzle->id,$model_type = 'App\Models\Puzzle',$user_id = Auth::user()->id,$hit = true);
        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $puzzle->award]));
        }

        // Attach user to quiz with hit
        $query = DB::table('award_user')->insert([
            'model_id'      => $data['data'],
            'award_id'     => $puzzle->award->id,
            'user_id'       => Auth::user()->id,
            'model_type'    => 'App\Models\Puzzle',
            'hit'           => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        $user_interaction = UserInteraction::create([
            'model_id' => $puzzle->id,
            'model_title' => $puzzle->title,
            'user_id' => Auth::user()->id,
            'hit' => true,
            'hit_created_at' => session('game_start'),
            'hit_updated_at' => Carbon::now()->format('Y-m-d H:i:s.u'),
        ]);

        $award_code = AwardCode::where([['award_id',$puzzle->award->id],['active',false]])->first();

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
        dd($query);
        if ($query) {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'error'], 400);
        }
    }
}
