<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\SaveAnswerRequest;
use App\Models\Question;
use App\Traits\UploadImageTrait;

class TriviaGameAnswerController extends Controller
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
        $answer = new Answer();

        return view('admin.games.triviagame.createanswer', [
            'answer'        => $answer,
            'question_id'   => $request->query('question'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveAnswerRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','answers',$request->file('featured_image'));
        }

        $answer = Answer::create($data);

        if ($answer->is_correct == true) {
            $this->reset_answers($answer);
        }

        return redirect(route('triviagameanswers.edit', ['tenant' => tenant('id'), 'answer' => $answer]))->with('status', trans('Answer saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        return view('admin.games.triviagame.createanswer', [
            'answer'  => $answer->load('question'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(SaveAnswerRequest $request, Answer $answer)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','answers',$request->file('featured_image'));
        }
        
        $answer->fill($data);
        $answer->save();

        if ($answer->is_correct == true) {
            $this->reset_answers($answer);
        }

        return redirect(route('triviagameanswers.edit', ['tenant' => tenant('id'), 'answer' => $answer]))->with('status', trans('Answer saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        //
    }

    public function reset_answers(Answer $answer)
    {
        $questions = Question::whereId($answer->question_id)
            ->with(['answers' => function($q) use ($answer){
                $q->whereNot('id',$answer->id);
            }])->get();

        foreach ($questions[0]->answers as $answer) {
            $answer->is_correct = false;
            $answer->save();
        }
    }
}
