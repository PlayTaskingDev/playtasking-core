<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShareQuizValidationRequest;
use App\Models\AwardCode;
use App\Models\ShareQuiz;
use App\Models\UserInteraction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\CheckParticipationTrait;
use App\Traits\CampaignsTrait;
use Illuminate\Support\Facades\DB;

class ShareQuizController extends Controller
{

    use CheckParticipationTrait, CampaignsTrait;

    public function share_quiz_index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
    }

    public function share_quiz_show($slug)
    {
        $today = Carbon::now()->toDateTimeString();
        $share_quiz = ShareQuiz::with('campaign')
            ->where(
            [
                ['slug', $slug],
                ['init_date','<',$today],
                ['end_date','>',$today]
            ])->firstOrFail();

        // Check if user has been participated
        $has_paticipated = $this->check_participation($model_id = $share_quiz->id,$model_type = 'App\Models\ShareQuiz',$user_id = Auth::user()->id);

        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $share_quiz->award]));
        }

        $campaign_games = $this->has_content_type($share_quiz->campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($share_quiz->campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($share_quiz->campaign->id, 'coupons');

        return view('dashboard.share_quizzes.show', [
            'share_quiz'        => $share_quiz,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function share_quiz_done(ShareQuizValidationRequest $request)
    {
        $data = $request->validated();

        $share_quiz = ShareQuiz::with('award')->findOrFail($data['share_quiz']);
        // Check if user has been participated and won
        $has_paticipated = $this->check_participation($model_id = $share_quiz->id,$model_type = 'App\Models\ShareQuiz',$user_id = Auth::user()->id,$hit = true);
        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $share_quiz->award]));
        }

        $shared = $this->share_url_scrapping($data['post_url'],$share_quiz);

        if(!$shared){
            return redirect()->back()->with('status',__('Your posted publication could not be validated. Post and try again.'));
        }

        // Attach user to quiz with hit
        $query = DB::table('award_user')->insert([
            'model_id'      => $data['share_quiz'],
            'award_id'     => $share_quiz->award->id,
            'user_id'       => Auth::user()->id,
            'model_type'    => 'App\Models\ShareQuiz',
            'hit'           => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        $user_interaction = UserInteraction::create([
            'model_id' => $share_quiz->id,
            'model_title' => $share_quiz->title,
            'user_id' => Auth::user()->id,
            'hit' => true,
            'hit_created_at' => Carbon::now(),
            'hit_updated_at' => Carbon::now(),
        ]);
        
        $award_code = AwardCode::where([['award_id',$share_quiz->award->id],['active',false]])->first();

        if (!is_null($award_code)) {
            $award_code->active = true;
            $award_code->user_id = Auth::user()->id;
            $award_code->save();

            $user_interaction->code = $award_code->code;
            $user_interaction->hit = true;
            $user_interaction->save();
        } else {
            return redirect()->route('game.out_of_coupons', ['tenant' => tenant('id')]);
        }

        if ($query) {
            return redirect()->route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $share_quiz->award]);
        } else {
            return redirect()->back()->with('status',__('Your posted publication could not be validated. Post and try again.'));
        }
    }
}
