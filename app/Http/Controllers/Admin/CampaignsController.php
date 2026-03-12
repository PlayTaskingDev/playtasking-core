<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveCampaignRequest;
use App\Models\CampaignSplashPage;
use App\Models\Campaign;
use App\Models\ContentType;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;

class CampaignsController extends Controller
{
    
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = Campaign::withCount(['quizzes','memory_quizzes','share_quizzes'])->get();

        return view('admin.campaigns.index', [
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
        
        return view('admin.campaigns.edit', [
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

        if($request->file('featured_image_url')){
            $data['featured_image_url'] = $this->uploadImage('gcs','campaign_splash_pages',$request->file('featured_image_url'));
        }


        CampaignSplashPage::create([
            'campaign_id' => $campaign->id,
            'instructions' => $data['instructions'] ?? null,
            'featured_image_url' => $data['featured_image_url'] ?? null,
            'featured_video_url' => $data['featured_video_url'] ?? null,
        ]);

        return redirect(route('campaigns.index', ['tenant' => tenant('id')]))->with('status', trans('Campaign saved successful'));
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

        // return response()->json([
        //     'message'  => 'Data retrive succesfully!',
        //     'data'   => [
        //             'campaign'              => $campaign->load('campaign_splash_page'),
        //             'time_slots'            => $time_slots,
        //             'game_content_type'     => $game_content_type,
        //             'tickets_content_type'  => $tickets_content_type,
        //             'coupons_content_type'  => $coupons_content_type,
        //             'has_games'             => $has_games,
        //             'has_tickets'           => $has_tickets,
        //             'has_coupons'           => $has_coupons,
        //         ]
        // ], 200);

        return view('admin.campaigns.edit', [
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
    public function update($id, SaveCampaignRequest $request)
    {
        
        $data = $request->validated();
        if( !$request->has('active') ){
            $data['active'] = false;
        }

        $campaign = Campaign::findorFail($id);
        $campaignSplashPage = $campaign->campaign_splash_page;
        $this->syncContentTypes($campaign, $request, $data);

        
        if($request->file('featured_image_url')){
            $data['featured_image_url'] = $this->uploadImage('gcs','campaign_splash_pages',$request->file('featured_image_url'));
        }

        if (isset($data['delete_image_holder_hidden']) && $data['delete_image_holder_hidden'] == true) {
            $campaignSplashPage->featured_image_url = null;
        }

        $campaignSplashPage->fill($data);
        $campaignSplashPage->save();

        $campaign->fill($data);
        $campaign->save();

        return redirect(route('campaigns.index', ['tenant' => tenant('id')]))->with('status', trans('Campaign saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign = Campaign::findorFail($id);
        $campaign->load(['quizzes','memory_quizzes','share_quizzes','content_types']);
        
        if ($campaign->quizzes && $campaign->quizzes->isNotEmpty() || $campaign->memory_quizzes && $campaign->memory_quizzes->isNotEmpty() || $campaign->share_quizzes && $campaign->share_quizzes->isNotEmpty()) {
            return redirect(route('campaigns.index', ['tenant' => tenant('id')]))->with('status', trans('Campaign can not be deleted if has games.'));
        } else {
            if ($campaign->campaign_splash_page) {
                $campaign->campaign_splash_page->delete();
            }
            if ($campaign->content_types && $campaign->content_types->isNotEmpty()) {
                $campaign->content_types()->detach();
            }
            $campaign->delete();
        }

        return redirect(route('campaigns.index', ['tenant' => tenant('id')]))->with('status', trans('Campaign deleted successful'));
    }

    /**
     * Synchronize content types with campaign based on request parameters
     *
     * @param  Campaign  $campaign
     * @param  Request   $request
     * @param  array     $data
     * @return void
     */
    private function syncContentTypes(Campaign $campaign, Request $request, array $data): void
    {
        $contentTypeConfigs = ['games', 'tickets', 'coupons'];

        foreach ($contentTypeConfigs as $typeKey) {
            $contentType = ContentType::where('system_name', $typeKey)->first();

            if (!$contentType) {
                continue;
            }

            if ($request->has($typeKey)) {
                // Attach if not already attached
                if (!$campaign->content_types->contains($contentType->id)) {
                    $campaign->content_types()->attach($contentType->id);
                }
            } else {
                // Detach if currently attached
                if ($campaign->content_types->contains($contentType->id)) {
                    $campaign->content_types()->detach($contentType->id);
                }
            }
        }
    }
}
