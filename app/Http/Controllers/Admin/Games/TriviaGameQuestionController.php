<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\SaveQuestionRequest;

class TriviaGameQuestionController extends Controller
{
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
        $question = new Question();

        return view('admin.games.triviagame.createquestion', [
            'question'  => $question,
            'quiz_id'   => $request->query('quiz_id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveQuestionRequest $request)
    {
        $data = $request->all();

        $question = Question::create($data);

        return redirect(route('triviagamequestions.edit', ['tenant' => tenant('id'), 'question' => $question]))->with('status', trans('Question saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::findOrFail($id); 
        return view('admin.games.triviagame.createquestion', [
            'question'  => $question->load('answers'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update($id, SaveQuestionRequest $request)
    {
        $data = $request->all();
        $question = Question::findOrFail($id);
        $question->fill($data);
        $question->save();

        return redirect(route('triviagamequestions.edit', ['tenant' => tenant('id'), 'question' => $question]))->with('status', trans('Question saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //
    }
}
