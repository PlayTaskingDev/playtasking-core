<?php

namespace App\Http\Controllers\Admin\Games;

use App\Exports\ContestInteractionsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveVoteContestRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Models\VoteContest;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;
use Maatwebsite\Excel\Facades\Excel;

class VoteGameController extends Controller
{
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vote_contests = VoteContest::orderBy('created_at','desc')->get();

        return view('admin.games.votegame.list', [
            'title'         => 'Panel | ' . trans('Vote Contests'),
            'description'   => 'Admin Panel',
            'vote_contests' => $vote_contests
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vote_contest = new VoteContest();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.votegame.edit', [
            'vote_contest'  => $vote_contest,
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveVoteContestRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','vote_contests',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','vote_contests',$request->file('featured_image_disabled'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','vote_contests',$request->file('game_banner'));
        }

        $data['asset_kb_size'] = intval($data['asset_kb_size']) * 1000;

        VoteContest::create($data);

        return redirect(route('votegames.index', ['tenant' => tenant('id')]))->with('status', trans('Vote contest saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VoteContest  $voteContest
     * @return \Illuminate\Http\Response
     */
    public function show(VoteContest $voteContest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VoteContest  $voteContest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $voteContest = VoteContest::findOrFail($id);
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();
        $arTypeAsset = [
            [
                'id' => 'photo',
                'name' => 'Photo'
            ],
            [
                'id' => 'video',
                'name' => 'Video'
            ]
        ];
        
        return view('admin.games.votegame.edit', [
            'vote_contest'  => $voteContest->load('campaign'),
            'campaigns'     => $campaigns,
            'type_asset'    => json_decode(json_encode($arTypeAsset)),
            'content_type'  => $content_type,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VoteContest  $voteContest
     * @return \Illuminate\Http\Response
     */
    public function update($id,SaveVoteContestRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','vote_contests',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','vote_contests',$request->file('featured_image_disabled'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','vote_contests',$request->file('game_banner'));
        }

        $voteContest = VoteContest::findOrFail($id);
        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $voteContest->game_banner = null;
        }

        $data['asset_kb_size'] = intval($data['asset_kb_size']) * 1000;

        if( !$request->has('show_ranking') ){
            $data['show_ranking'] = false;
        }

        if( !$request->has('btn_border') ){
            $data['btn_border'] = false;
        }

        if( !$request->has('btn_shadow') ){
            $data['btn_shadow'] = false;
        }

        $voteContest->fill($data);
        $voteContest->save();

        return redirect(route('votegames.index', ['tenant' => tenant('id')]))->with('status', trans('Vote contest saved successful'));
    }

    public function export($model_id)
    {
       $rows_collection = DB::table("vote_contest_assets as vca")->where('vca.vote_contest_id', $model_id)
            ->join('users as u', 'u.id', '=', 'vca.user_id')
            ->selectRaw('
               u.id as user_id,
               u.name as user_name,
               u.email as user_email,
               vca.title as description,
               vca.asset_url as asset_url,
               vca.created_at as submission_date
            ')
            ->get();
        return Excel::download(
            new ContestInteractionsExport($rows_collection),
            'user_interactions_contests.xlsx'
        );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoteContest  $voteContest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $voteContest = VoteContest::findOrFail($id);
        $voteContest->load('contest_assets.votations');

        // delete all assets uploaded by users (videos and photos)
        if ($voteContest->contest_assets && $voteContest->contest_assets->isNotEmpty()) {
            foreach ($voteContest->contest_assets as $contest_asset) {
                if ($contest_asset->votations && $contest_asset->votations->isNotEmpty()){
                    foreach ($contest_asset->votations as $votation) {
                        $votation->delete();
                    }
                }
                $contest_asset->delete();
            }
        }

        $voteContest->delete();

        return redirect(route('votegames.index', ['tenant' => tenant('id')]))->with('status', trans('Vote contest deleted successful'));

    }
}
