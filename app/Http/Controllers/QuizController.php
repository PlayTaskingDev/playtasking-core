<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Traits\CheckParticipationTrait;
use App\Models\AwardCode;
use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\UserInteraction;
use App\Traits\CampaignsTrait;

class QuizController extends Controller
{
    use CheckParticipationTrait, CampaignsTrait;

    public function quiz_index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
    }

    public function quiz_show($slug)
    {
        $today = Carbon::now()->toDateTimeString();
        $quiz = Quiz::with(['questions.answers' => function($q){
            $q->inRandomOrder();
        }, 'campaign'])->where(
            [
                ['slug', $slug],
                ['init_date','<',$today],
                ['end_date','>',$today]
            ])->firstOrFail();

        // Check if user has been participated and won
        $has_paticipated = $this->check_participation($model_id = $quiz->id,$model_type = 'App\Models\Quiz',$user_id = Auth::user()->id,$hit = true);
        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $quiz->award]));
        }

        // Check if user has been participated and failed
        $has_paticipated = $this->check_participation($model_id = $quiz->id,$model_type = 'App\Models\Quiz',$user_id = Auth::user()->id);
        if (!is_null($has_paticipated)) {
            return redirect()->route('game.failed', [
                'tenant' => tenant('id'), 
                'model_type' =>  $quiz->award->model_type, 
                'model' => $quiz
            ]);
        }

        $campaign_games = $this->has_content_type($quiz->campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($quiz->campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($quiz->campaign->id, 'coupons');
        return view('dashboard.quizzes.show', [
            'quiz'              => $quiz,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function quiz_evaluate(Request $request)
    {
        
        $rules = [
            'answers.*.answer'  => ['required','exists:answers,id'],
            'quid'              => ['required','exists:quizzes,id']
        ];
        $validator = validator($request->all(), $rules);
        if ($validator->fails()) {
            return back()->with('status', 'error');
        }
        $data = $validator->validated();

        $quiz = Quiz::whereId($data['quid'])->with(
            [
                'answers' => function($q){
                    $q->where('is_correct',true);
                },
                'award'
            ])->first();

        // Check if user has been participated and won
        $has_paticipated = $this->check_participation($model_id = $quiz->id,$model_type = 'App\Models\Quiz',$user_id = Auth::user()->id,$hit = true);
        if (!is_null($has_paticipated)) {
            session()->forget('game_start');
            session()->forget('game_duration');
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $quiz->award]));
        }

        // Check if user has been participated and failed
        $has_paticipated = $this->check_participation($model_id = $quiz->id,$model_type = 'App\Models\Quiz',$user_id = Auth::user()->id);
        if (!is_null($has_paticipated)) {
            dd($has_paticipated);
            session()->forget('game_start');
            session()->forget('game_duration');
            return redirect()->route('game.failed', [
                'tenant' => tenant('id'), 
                'model_type' =>  $quiz->award->model_type, 
                'model' => $quiz
            ]);
        }
        // Convert to a less complex array the responses ID
        $answers_array = [];
        foreach ($data['answers'] as $request_answers_array) {
            foreach ($request_answers_array as $answer_id) {
                array_push($answers_array,$answer_id);
            }
        }

        // Convert to a less complex array the quiz correct responses ID
        $quiz_answers_array = [];
        foreach ($quiz->answers as $answer) {
            array_push($quiz_answers_array,$answer->id);
        }
        
        sort($answers_array);
        sort($quiz_answers_array);
        
        // Compare responses
        if ($answers_array === $quiz_answers_array) {
            $hit = true;
        } else {
            $hit = false;
        }
        
        // Attach user to quiz with hit or not
        $user_interaction = $this->attach_user_to_quiz($data, $quiz, $hit);
        session()->forget('game_start');
        session()->forget('game_duration');
        

        if ($hit === true) {
            $award_code = AwardCode::where([['award_id',$quiz->award->id],['active',false]])->first();

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

            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $quiz->award]));
        } else {
            return redirect()->route('game.failed', [
                'tenant' => tenant('id'), 
                'model_type' =>  $quiz->award->model_type, 
                'model' => $quiz
            ]);
        }
        
    }

    public function quiz_timer_out(Request $request){
        $quiz = Quiz::whereId($request->input('quid'))->with('award')->first();
        $this->attach_user_to_quiz($request->all(), $quiz, false);
        return redirect()->route('game.failed', [
                'tenant' => tenant('id'), 
                'model_type' =>  $quiz->award->model_type, 
                'model' => $quiz
            ]);
    }

    public function attach_user_to_quiz($data, $quiz, $hit){
        DB::table('award_user')->insert([
            'model_id'      => $data['quid'],
            'award_id'     => $quiz->award->id,
            'user_id'       => Auth::user()->id,
            'model_type'    => 'App\Models\Quiz',
            'hit'           => $hit,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        $user_interaction = UserInteraction::create([
            'model_id' => $quiz->id,
            'model_title' => $quiz->title,
            'user_id' => Auth::user()->id,
            'hit_created_at' => Carbon::now(),
            'hit_updated_at' => Carbon::now(),
        ]);
        return $user_interaction;
    }
}
