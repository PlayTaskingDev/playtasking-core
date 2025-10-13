<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoteContest;
use App\Models\User;
use App\Models\UserInteraction;
use App\Models\VoteContest;
use App\Models\VoteContestAsset;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Traits\CheckParticipationTrait;
use App\Traits\CampaignsTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Support\Str;

class VoteContestController extends Controller
{
    use CheckParticipationTrait, CampaignsTrait, UploadImageTrait;
    
    public function vote_contest_index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
    }

    public function vote_contest_show($slug)
    {
        $today = Carbon::now()->toDateTimeString();
        $vote_contest = VoteContest::with('campaign')
            ->where(
            [
                ['slug', $slug],
                ['init_date','<',$today],
                ['end_date','>',$today]
            ])->firstOrFail();

        // Check if user has been participated
        $has_paticipated = $this->check_participation($model_id = $vote_contest->id,$model_type = 'App\Models\VoteContest',$user_id = Auth::user()->id);

        if (!is_null($has_paticipated)) {
            return redirect()->route('vote_contest.ranking', ['tenant' => tenant('id'), 'slug' => $vote_contest->slug]);
        }

        $campaign_games = $this->has_content_type($vote_contest->campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($vote_contest->campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($vote_contest->campaign->id, 'coupons');

        return view('dashboard.vote_contests.show', [
            'vote_contest'      => $vote_contest,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function vote_contest_store(StoreVoteContest $request)
    {
        $data = $request->validated();
        $vote_contest = VoteContest::find($data['vote_contest']);

        // Check if user has been participated
        $has_paticipated = $this->check_participation($model_id = $vote_contest->id,$model_type = 'App\Models\VoteContest',$user_id = Auth::user()->id);

        if (!is_null($has_paticipated)) {
            return redirect()->route('vote_contest.ranking', ['tenant' => tenant('id'), 'slug' => $vote_contest->slug]);
        }

        if ($vote_contest->asset_type == 'photo') {
            $asset_url = $this->uploadImage('gcs','vote_contest_assets',$request->file('asset'));
        } else {
            $video_resource_url = Vimeo::upload($request->file('asset'),[
                'name'          => $data['title'],
            ]);
            $asset_url = Str::replace('/videos/', 'https://vimeo.com/', $video_resource_url);
        }

        $asset = VoteContestAsset::create([
            'title'             => $data['title'],
            'asset_url'         => $asset_url,
            'user_id'           => auth()->user()->id,
            'vote_contest_id'   => $vote_contest->id
        ]);

        // Attach user to vote contest participation
        DB::table('award_user')->insert([
            'model_id'      => $data['vote_contest'],
            'award_id'      => null,
            'user_id'       => Auth::user()->id,
            'model_type'    => 'App\Models\VoteContest',
            'hit'           => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        UserInteraction::create([
            'model_id' => $vote_contest->id,
            'model_title' => $vote_contest->title,
            'user_id' => Auth::user()->id,
            'hit' => true,
            'hit_created_at' => Carbon::now(),
            'hit_updated_at' => Carbon::now(),
        ]);

        return redirect()->route('vote_contest.ranking', ['tenant' => tenant('id'), 'slug' => $vote_contest->slug]);
 
    }

    public function vote_contest_ranking($slug)
    {
        $vote_contest = VoteContest::with('campaign')->where('slug', $slug)->firstOrFail();

        $campaign = $this->get_current_campaign();
        $campaign_games = $this->has_content_type($campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($campaign->id, 'coupons');

        $user = User::with(['contest_assets' => function(Builder $q) use ($vote_contest){
            $q->where('vote_contest_id',$vote_contest->id);
        }])->where('id',Auth::user()->id)->first();

        // if user has not participated can not see the ranking
        if ($user->contest_assets->isEmpty()) {
            return redirect()->route('vote_contest.show', ['tenant' => tenant('id'), 'slug' => $vote_contest->slug]);
        }
        $top_ranking = VoteContestAsset::with('user')->whereHas('user')->where([
            ['points','>',0],['vote_contest_id',$vote_contest->id]
            ])->orderBy('points', 'desc')->limit(10)->get();

        if ($top_ranking->isNotEmpty()) {
            // iterate over collection to know if the logged user is in the top
            foreach ($top_ranking as $contest_asset) {
                if (!is_null($contest_asset->user) && $contest_asset->user->id == $user->id) {
                    $user_in_top = true;
                    break;
                } else {
                    $user_in_top = false;
                }
            }
            // get top 3 items
            $top_users = $top_ranking->slice(0,3);
            // remove the top 3 items to get places 4 to 10
            $top_ten_users = $top_ranking->splice(3);
        } else {
            $top_users = null;
            $user_in_top = false;
            $top_ten_users = null;
        }

        return view('dashboard.vote_contests.ranking', [
            'campaign'          => $campaign,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
            'vote_contest'      => $vote_contest,
            'top_users'         => $top_users ? $top_users->all() : [],
            'top_ten_users'     => $top_ten_users ? $top_ten_users->all() : [],
            'user_in_top'       => $user_in_top,
            'user'              => $user,
        ]);
    }
}
