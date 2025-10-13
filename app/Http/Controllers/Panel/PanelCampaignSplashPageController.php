<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveCampaignSplashPageRequest;
use App\Models\CampaignSplashPage;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;

class PanelCampaignSplashPageController extends Controller
{
    use UploadImageTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $campaignSplashPage = new CampaignSplashPage();

        return view('panel.campaign_splash_pages.edit',[
            'campaign_splash_page'  => $campaignSplashPage,
            'campaign_id'           => $request->query('campaign'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveCampaignSplashPageRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image_url')){
            $data['featured_image_url'] = $this->uploadImage('gcs','campaign_splash_pages',$request->file('featured_image_url'));
        }

        $campaignSplashPage = CampaignSplashPage::create($data);

        return redirect(route('campaign_splash_page.edit', ['tenant' => tenant('id'), 'campaign_splash_page' => $campaignSplashPage]))->with('status', trans('Welcome page saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CampaignSplashPage  $campaignSplashPage
     * @return \Illuminate\Http\Response
     */
    public function show(CampaignSplashPage $campaignSplashPage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CampaignSplashPage  $campaignSplashPage
     * @return \Illuminate\Http\Response
     */
    public function edit(CampaignSplashPage $campaignSplashPage)
    {
        return view('panel.campaign_splash_pages.edit',[
            'campaign_splash_page' => $campaignSplashPage
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CampaignSplashPage  $campaignSplashPage
     * @return \Illuminate\Http\Response
     */
    public function update(SaveCampaignSplashPageRequest $request, CampaignSplashPage $campaignSplashPage)
    {
        $data = $request->all();

        if($request->file('featured_image_url')){
            $data['featured_image_url'] = $this->uploadImage('gcs','campaign_splash_pages',$request->file('featured_image_url'));
        }

        if (isset($data['delete_image_holder_hidden']) && $data['delete_image_holder_hidden'] == true) {
            $campaignSplashPage->featured_image_url = null;
        }

        $campaignSplashPage->fill($data);
        $campaignSplashPage->save();

        return redirect(route('campaign_splash_page.edit', ['tenant' => tenant('id'), 'campaign_splash_page' => $campaignSplashPage]))->with('status', trans('Welcome page saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CampaignSplashPage  $campaignSplashPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(CampaignSplashPage $campaignSplashPage)
    {
        //
    }
}
