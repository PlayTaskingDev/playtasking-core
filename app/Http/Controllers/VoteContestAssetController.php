<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteContestAssetRequest;
use App\Models\VoteContest;
use App\Models\VoteContestAsset;
use App\Models\VoteContestVotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoteContestAssetController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(VoteContestAsset::class, 'asset');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VoteContestAsset  $asset
     * @return \Illuminate\Http\Response
     */
    public function show(VoteContestAsset $asset)
    {
        return view('dashboard.vote_contest_assets.show', [
            'title'         => $asset->title,
            'description'   => get_app_setting('app_description'),
            'classes'       => 'pages',
            'asset'         => $asset->load('vote_contest'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoteContestAsset  $asset
     * @return \Illuminate\Http\Response
     */
    public function vote(VoteContestAssetRequest $request)
    {
        $data = $request->validated();

        $vote_contest_asset = VoteContestAsset::with('vote_contest')->find($data['id']);

        // prevent a user vote by its own asset
        if (!is_null(auth()->user()) && auth()->user()->id == $vote_contest_asset->user_id) {
            return redirect()->route('asset.show', ['tenant' => tenant('id'), 'asset' => $vote_contest_asset]);
        }

        $points = $vote_contest_asset->vote_contest->points_per_vote;

        VoteContestVotation::create([
            'email'                 => $data['email'],
            'points'                => $points,
            'vote_contest_asset_id' => $data['id']
        ]);

        $vote_contest_asset->points = $vote_contest_asset->points + $points;
        $vote_contest_asset->save();

        return redirect()->route('asset.show', ['tenant' => tenant('id'), 'asset' => $vote_contest_asset])->with('success',__('Thanks for voting!'));
    }

    public function destroy(VoteContestAsset $asset)
    {
        $vote_contest = $asset->vote_contest;

        // Delete user interaction
        DB::table('award_user')->where([
            'model_id'      => $vote_contest->id,
            'user_id'       => Auth::user()->id,
            'model_type'    => \App\Models\VoteContest::class,
        ])->delete();

        $asset->delete();

        return redirect()->route('vote_contest.show', ['tenant' => tenant('id'), 'slug' => $vote_contest->slug]);
    }
}
