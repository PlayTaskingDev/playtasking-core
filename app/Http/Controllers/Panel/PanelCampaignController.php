<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveCampaignRequest;
use App\Models\Campaign;
use App\Models\ContentType;
use Illuminate\Http\Request;

class PanelCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = Campaign::withCount(['quizzes','memory_quizzes','share_quizzes'])->get();

        return view('panel.campaigns.index', [
            'title'             => 'Panel | ' . trans('Campaigns'),
            'description'       => 'Admin Panel',
            'campaigns'         => $campaigns
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $campaign = new Campaign();
        $time_slots = get_time_slots();
        $game_content_type = ContentType::where('system_name','games')->first();
        $tickets_content_type = ContentType::where('system_name','tickets')->first();
        $coupons_content_type = ContentType::where('system_name','coupons')->first();
        
        return view('panel.campaigns.edit', [
            'campaign'              => $campaign,
            'time_slots'            => $time_slots,
            'game_content_type'     => $game_content_type,
            'tickets_content_type'  => $tickets_content_type,
            'coupons_content_type'  => $coupons_content_type,
            'has_games'             => NULL,
            'has_tickets'           => NULL,
            'has_coupons'           => NULL,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveCampaignRequest $request)
    {
        $data = $request->validated();

        $campaign = Campaign::create($data);

        if( $request->has('games') ){
            $campaign->content_types()->attach($data['games']);
        }

        if( $request->has('tickets') ){
            $campaign->content_types()->attach($data['tickets']);
        }

        if( $request->has('coupons') ){
            $campaign->content_types()->attach($data['coupons']);
        }

        return redirect(route('panel.campaign.index', ['tenant' => tenant('id')]))->with('status', trans('Campaign saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show(Campaign $campaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $campaign)
    {
        $time_slots = get_time_slots();
        $game_content_type = ContentType::where('system_name','games')->first();
        $tickets_content_type = ContentType::where('system_name','tickets')->first();
        $coupons_content_type = ContentType::where('system_name','coupons')->first();

        $has_games = $campaign->content_types->contains($game_content_type->id);
        $has_tickets = $campaign->content_types->contains($tickets_content_type->id);
        $has_coupons = $campaign->content_types->contains($coupons_content_type->id);

        return view('panel.campaigns.edit', [
            'campaign'              => $campaign->load('campaign_splash_page'),
            'time_slots'            => $time_slots,
            'game_content_type'     => $game_content_type,
            'tickets_content_type'  => $tickets_content_type,
            'coupons_content_type'  => $coupons_content_type,
            'has_games'             => $has_games,
            'has_tickets'           => $has_tickets,
            'has_coupons'           => $has_coupons,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(SaveCampaignRequest $request, Campaign $campaign)
    {
        $data = $request->validated();

        if( !$request->has('active') ){
            $data['active'] = false;
        }

        if( $request->has('games') ){
            if (!$campaign->content_types->contains($data['games'])) {
                $campaign->content_types()->attach($data['games']);
            }
        } else {
            $games_content_type = ContentType::where('system_name','games')->first();
            if ($campaign->content_types->contains($games_content_type->id)) {
                $campaign->content_types()->detach($games_content_type->id);
            }
        }

        if( $request->has('tickets')){
            if (!$campaign->content_types->contains($data['tickets'])) {
                $campaign->content_types()->attach($data['tickets']);
            }
        } else {
            $tickets_content_type = ContentType::where('system_name','tickets')->first();
            if ($campaign->content_types->contains($tickets_content_type->id)) {
                $campaign->content_types()->detach($tickets_content_type->id);
            }
        }

        if( $request->has('coupons')){
            if (!$campaign->content_types->contains($data['coupons'])) {
                $campaign->content_types()->attach($data['coupons']);
            }
        } else {
            $coupons_content_type = ContentType::where('system_name','coupons')->first();
            if ($campaign->content_types->contains($coupons_content_type->id)) {
                $campaign->content_types()->detach($coupons_content_type->id);
            }
        }

        $campaign->fill($data);
        $campaign->save();

        return redirect(route('panel.campaign.index', ['tenant' => tenant('id')]))->with('status', trans('Campaign saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->load(['quizzes','memory_quizzes','share_quizzes','content_types']);
        
        if ($campaign->quizzes && $campaign->quizzes->isNotEmpty() || $campaign->memory_quizzes && $campaign->memory_quizzes->isNotEmpty() || $campaign->share_quizzes && $campaign->share_quizzes->isNotEmpty()) {
            return redirect(route('panel.campaign.index', ['tenant' => tenant('id')]))->with('status', trans('Campaign can not be deleted if has games.'));
        } else {
            if ($campaign->campaign_splash_page) {
                $campaign->campaign_splash_page->delete();
            }
            if ($campaign->content_types && $campaign->content_types->isNotEmpty()) {
                $campaign->content_types()->detach();
            }
            $campaign->delete();
        }

        return redirect(route('panel.campaign.index', ['tenant' => tenant('id')]))->with('status', trans('Campaign deleted successful'));
    }
}
