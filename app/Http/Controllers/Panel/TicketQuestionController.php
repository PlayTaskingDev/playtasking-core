<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\ImportTicketQuestionRequest;
use App\Http\Requests\Panel\SaveTicketQuestionRequest;
use App\Imports\TicketQuestionsImport;
use App\Models\TicketQuestion;
use Illuminate\Http\Request;

class TicketQuestionController extends Controller
{
    public function index()
    {
        $questions = TicketQuestion::orderBy('created_at','desc')->get();

        return response()->view('panel.ticket_questions.index',[
            'title'         => 'Panel | ' . trans('Ticket Questions'),
            'description'   => 'Admin Panel',
            'questions'     => $questions,
        ]);
    }

    public function create()
    {
        $ticketQuestion = new TicketQuestion();

        return view('panel.ticket_questions.edit', [
            'ticketQuestion'  => $ticketQuestion,
        ]);
    }

    public function store(SaveTicketQuestionRequest $request)
    {
        $data = $request->all();

        $ticketQuestion = TicketQuestion::create($data);

        return redirect(route('ticketQuestion.edit', ['tenant' => tenant('id'), 'ticketQuestion' => $ticketQuestion]))->with('status', trans('Question saved successful'));
    }

    public function edit(TicketQuestion $ticketQuestion)
    {
        return view('panel.ticket_questions.edit', [
            'ticketQuestion'  => $ticketQuestion->load('ticket_answers'),
        ]);
    }

    public function update(SaveTicketQuestionRequest $request, TicketQuestion $ticketQuestion)
    {
        $data = $request->all();
        $ticketQuestion->fill($data);
        $ticketQuestion->save();

        return redirect(route('ticketQuestion.edit', ['tenant' => tenant('id'), 'ticketQuestion' => $ticketQuestion]))->with('status', trans('Question saved successful'));
    }

    public function download_sample()
    {
        $filePath = public_path("/storage/assets/ticket_questions_sample.xlsx");
        return response()->download($filePath);
    }

    public function import_show()
    {
        return response()->view('panel.ticket_questions.import',[
            'title'         => 'Panel | ' . trans('Import Ticket Questions'),
            'description'   => 'Admin Panel',
        ]);
    }

    public function import(ImportTicketQuestionRequest $request)
    {
        ini_set('upload_max_filesize', '8M');
        ini_set('post_max_size', '8M');
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '2400');

        try {
            $import = new TicketQuestionsImport();
            $import->import($request->file('file'), null, null);

            return redirect(route('ticketQuestion.index', ['tenant' => tenant('id')]))->with('status', trans('Questions saved successful'));

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $questions = TicketQuestion::orderBy('created_at','desc')->get();

            return response()->view('panel.ticket_questions.index',[
                'title'         => 'Panel | ' . trans('Ticket Questions'),
                'description'   => 'Admin Panel',
                'failures'      => $failures,
                'questions'     => $questions
            ]);
        }
    }

    public function destroy(TicketQuestion $ticketQuestion)
    {
        $ticketQuestion->load('ticket_answers');

        if ($ticketQuestion->ticket_answers) {
            foreach ($ticketQuestion->ticket_answers as $answer) {
                $answer->delete();
            }
        }

        $ticketQuestion->delete();

        return redirect(route('ticketQuestion.index', ['tenant' => tenant('id')]))->with('status', trans('Question deleted successful'));
    }
}
