<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\ContentType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\CampaignsTrait;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    use CampaignsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function splash_page()
    {
        $now = Carbon::now()->toDateTimeString();
        $active_campaign = Campaign::with(['campaign_splash_page','content_types'])
            ->where([['active',true],['init_date','<',$now],['end_date','>',$now]])
            ->first();

        if (!is_null($active_campaign)) {
            return view('dashboard.campaigns.splash', [
                'active_campaign'   => $active_campaign,
            ]);
        } else {
            return redirect()->route('dashboard.nogame', ['tenant' => tenant('id')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $now = Carbon::now()->toDateTimeString();
        $campaign = Campaign::with(
                    [
                        'share_quizzes' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'quizzes' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'memory_quizzes' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'vote_contests' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'click_wins' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'aplazo_games' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'puzzles' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                        'catch_games' => function($q) use ($now)
                            {
                                $q->where([['init_date','<',$now],['end_date','>',$now]]);
                            },
                    ])
                    ->where([['slug', $slug],['active',true],['init_date','<',$now],['end_date','>',$now]])
                    ->first();

        if (!is_null($campaign)) {
            $campaign_games = $this->has_content_type($campaign->id, 'games');
            $campaign_tickets = $this->has_content_type($campaign->id, 'tickets');
            $campaign_coupons = $this->has_content_type($campaign->id, 'coupons');

            return view('dashboard.campaigns.show', [
                'campaign'          => $campaign,
                'campaign_games'    => $campaign_games,
                'campaign_tickets'  => $campaign_tickets,
                'campaign_coupons'  => $campaign_coupons,
                'user_games'        => Auth::user()->getGames(),
            ]);
        } else {
            return redirect()->route('dashboard.nogame', ['tenant' => tenant('id')]);
        }

        
    }

    public function record_game_start(Request $request)
    {
        // Format: 2025-08-02 20:15:37.123456
        $ts = Carbon::now()->format('Y-m-d H:i:s.u');
        $request->session()->put('game_start', $ts);

        return response()->json(['game_start' => $ts]);
    }
}
