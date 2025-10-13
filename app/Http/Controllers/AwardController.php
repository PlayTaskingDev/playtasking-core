<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Award;
use App\Models\AwardCode;
use App\Models\Code;
use App\Models\UserInteraction;
use Illuminate\Http\Request;
use App\Traits\CheckParticipationTrait;
use Illuminate\Support\Str;
use App\Traits\CampaignsTrait;
use Illuminate\Support\Facades\DB;

class AwardController extends Controller
{
    use CheckParticipationTrait, CampaignsTrait;

    public function __construct()
    {
        $this->authorizeResource(Award::class, 'award');
    }

    public function index()
    {
        $campaign = $this->get_current_campaign();
        $campaign_games = $this->has_content_type($campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($campaign->id, 'coupons');

        $award_codes = AwardCode::with('award.awardable')
            ->where('user_id', Auth::user()->id)
            ->where('active', true)
            ->get();

        // Pull code hunter participation when mode is multiple instead of unique
        $code_hunter = Code::with('award.awardable')
            ->where([
                ['campaign_id', $campaign->id],
                ['active', true],
                ['type', 'multiple']
            ])->first();

        $participation = false;
        if ($code_hunter){
            $code_hunter_interaction = UserInteraction::where([
                ['user_id', Auth::user()->id],
                ['model_id', $code_hunter->id],
            ])->first();

            if ($code_hunter_interaction){
                $participation = true;
            }
        }

        return view('dashboard.awards.index', [
            'campaign'                  => $campaign,
            'campaign_games'            => $campaign_games,
            'campaign_tickets'          => $campaign_tickets,
            'campaign_coupons'          => $campaign_coupons,
            'award_codes'               => $award_codes,
            'active_icon'               => 'coupons',
            'code_hunter'               => $participation ? $code_hunter : null,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function show(Award $award, Request $request, $code_id = null)
    {
        if ($request->session()->get('coupon_success') == true) {
            $award_code = DB::table('award_codes')->where('award_id', $award->id)->whereNull('user_id')->first();
        } elseif ($code_id) {
            $award_code = DB::table('award_codes')->where([['award_id', $award->id],['user_id',Auth::user()->id],['id',$code_id]])->first();
        }
        else {
            $award_code = DB::table('award_codes')->where([['award_id', $award->id],['user_id',Auth::user()->id]])->first();
        }
        
        if ($award_code) {
            $campaign = $this->get_current_campaign();
            $campaign_games = $this->has_content_type($campaign->id, 'games');
            $campaign_tickets = $this->has_content_type($campaign->id, 'tickets');
            $campaign_coupons = $this->has_content_type($campaign->id, 'coupons');
            
            $active_icon = $award->model_type == 'code' ? 'coupons' : 'games';
            
            return view('dashboard.awards.show', [
                'award'             => $award,
                'award_code'        => $award_code,
                'campaign'          => $campaign,
                'campaign_games'    => $campaign_games,
                'campaign_tickets'  => $campaign_tickets,
                'campaign_coupons'  => $campaign_coupons,
                'active_icon'       => $active_icon,
            ]);
        } else {
            return redirect()->route('game.out_of_coupons', ['tenant' => tenant('id')]);
        }
    }

    public function game_failed($model_type, $model)
    {
        $model_classname = 'App\\Models\\' . Str::ucfirst($model_type);
        $model = $model_classname::find($model);

        $campaign = $this->get_current_campaign();
        $campaign_games = $this->has_content_type($campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($campaign->id, 'coupons');
        
        return view('dashboard.awards.loser', [
            'model'             => $model,
            'campaign'          => $campaign,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function out_of_coupons()
    {
        $campaign = $this->get_current_campaign();
        $campaign_games = $this->has_content_type($campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($campaign->id, 'coupons');

        return view('dashboard.awards.out_of_coupons', [
            'campaign'          => $campaign,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }
}
