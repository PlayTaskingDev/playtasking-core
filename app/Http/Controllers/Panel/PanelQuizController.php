<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\SaveQuizRequest;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Traits\UploadImageTrait;

class PanelQuizController extends Controller
{
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = Quiz::withCount('questions')->orderBy('created_at','desc')->get();

        return view('panel.quizzes.index', [
            'title'         => 'Panel | ' . trans('Quizzes'),
            'description'   => 'Admin Panel',
            'quizzes'       => $quizzes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quiz = new Quiz();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('panel.quizzes.edit', [
            'quiz'          => $quiz,
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
    public function store(SaveQuizRequest $request)
    {
        $data = $request->all();
        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','quizzes',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','quizzes',$request->file('featured_image_disabled'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','quizzes',$request->file('failed_image'));
        }
        if($request->file('failed_image_out_time')){
            $data['failed_image_out_time'] = $this->uploadImage('gcs','quizzes',$request->file('failed_image_out_time'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','quizzes',$request->file('game_banner'));
        }

        Quiz::create($data);

        return redirect(route('quizzes.index', ['tenant' => tenant('id')]))->with('status', trans('Quiz saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show(Quiz $quiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();
        dd($quiz);
        return view('panel.quizzes.edit', [
            'quiz'          => $quiz->load('questions','award','campaign'),
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(SaveQuizRequest $request, Quiz $quiz)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','quizzes',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','quizzes',$request->file('featured_image_disabled'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','quizzes',$request->file('failed_image'));
        }
        if($request->file('failed_image_out_time')){
            $data['failed_image_out_time'] = $this->uploadImage('gcs','quizzes',$request->file('failed_image_out_time'));
        }
        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','quizzes',$request->file('game_banner'));
        }

        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $quiz->game_banner = null;
        }

        if( !$request->has('btn_border') ){
            $data['btn_border'] = false;
        }

        if( !$request->has('btn_shadow') ){
            $data['btn_shadow'] = false;
        }

        $quiz->fill($data);
        $quiz->save();

        return redirect(route('quizzes.index', ['tenant' => tenant('id')]))->with('status', trans('Quiz saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->load(['questions','answers','award','coupons']);

        if ($quiz->answers && $quiz->answers->isNotEmpty()) {
            foreach ($quiz->answers as $answer) {
                $answer->delete();
            }
        }

        if ($quiz->questions && $quiz->questions->isNotEmpty()) {
            foreach ($quiz->questions as $question) {
                $question->delete();
            }
        }

        if ($quiz->coupons && $quiz->coupons->isNotEmpty()) {
            foreach ($quiz->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($quiz->award) {
            $quiz->award->delete();
        }

        $quiz->delete();

        return redirect(route('quizzes.index', ['tenant' => tenant('id')]))->with('status', trans('Quiz deleted successful'));
    }
}
