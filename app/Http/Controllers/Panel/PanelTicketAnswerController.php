<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveTicketAnswerRequest;
use App\Models\TicketAnswer;
use App\Models\TicketQuestion;
use Illuminate\Http\Request;

class PanelTicketAnswerController extends Controller
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
        $ticketAnswer = new TicketAnswer();

        return view('panel.ticket_answers.edit', [
            'ticketAnswer'          => $ticketAnswer,
            'ticket_question_id'    => $request->query('ticketQuestion'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveTicketAnswerRequest $request)
    {
        $data = $request->all();

        $ticketAnswer = TicketAnswer::create($data);

        if ($ticketAnswer->is_correct == true) {
            $this->reset_answers($ticketAnswer);
        }

        return redirect(route('ticketAnswer.edit', ['tenant' => tenant('id'), 'ticketAnswer' => $ticketAnswer]))->with('status', trans('Answer saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TicketAnswer  $ticketAnswer
     * @return \Illuminate\Http\Response
     */
    public function show(TicketAnswer $ticketAnswer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TicketAnswer  $ticketAnswer
     * @return \Illuminate\Http\Response
     */
    public function edit(TicketAnswer $ticketAnswer)
    {
        return view('panel.ticket_answers.edit', [
            'ticketAnswer'  => $ticketAnswer->load('ticket_question'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TicketAnswer  $ticketAnswer
     * @return \Illuminate\Http\Response
     */
    public function update(SaveTicketAnswerRequest $request, TicketAnswer $ticketAnswer)
    {
        $data = $request->all();

        $ticketAnswer->fill($data);
        $ticketAnswer->save();

        if ($ticketAnswer->is_correct == true) {
            $this->reset_answers($ticketAnswer);
        }

        return redirect(route('ticketAnswer.edit', ['tenant' => tenant('id'), 'ticketAnswer' => $ticketAnswer]))->with('status', trans('Answer saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TicketAnswer  $ticketAnswer
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketAnswer $ticketAnswer)
    {
        $ticket_question = $ticketAnswer->ticket_question;
        $ticketAnswer->delete();

        return redirect(route('ticketQuestion.edit', ['tenant' => tenant('id'), 'ticketQuestion' => $ticket_question]))->with('status', trans('Question saved successful'));
    }

    public function reset_answers(TicketAnswer $answer)
    {
        $questions = TicketQuestion::whereId($answer->ticket_question_id)
            ->with(['ticket_answers' => function($q) use ($answer){
                $q->whereNot('id',$answer->id);
            }])->get();

        foreach ($questions[0]->ticket_answers as $answer) {
            $answer->is_correct = false;
            $answer->save();
        }
    }
}
