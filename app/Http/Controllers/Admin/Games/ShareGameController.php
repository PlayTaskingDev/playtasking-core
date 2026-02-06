<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveShareQuizRequest;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Models\ShareQuiz;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;

class ShareGameController extends Controller
{
    use UploadImageTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $share_quizzes = ShareQuiz::all();

        return view('admin.games.sharegame.list', [
            'title'             => 'Panel | ' . trans('Share Quizzes'),
            'description'       => 'Admin Panel',
            'share_quizzes'     => $share_quizzes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $share_quiz = new ShareQuiz();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.sharegame.edit', [
            'share_quiz'    => $share_quiz,
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
    public function store(SaveShareQuizRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','share_quizzes',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','quizzes',$request->file('featured_image_disabled'));
        }

        if($request->file('featured_image_url')){
            $data['featured_image_url'] = $this->uploadImage('gcs','share_quizzes',$request->file('featured_image_url'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','share_quizzes',$request->file('game_banner'));
        }

        ShareQuiz::create($data);

        return redirect(route('sharegames.index', ['tenant' => tenant('id')]))->with('status', trans('Share quiz saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShareQuiz  $shareQuiz
     * @return \Illuminate\Http\Response
     */
    public function show(ShareQuiz $shareQuiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShareQuiz  $shareQuiz
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shareQuiz = ShareQuiz::findOrFail($id);
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.sharegame.edit', [
            'share_quiz'    => $shareQuiz->load('award','campaign'),
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShareQuiz  $shareQuiz
     * @return \Illuminate\Http\Response
     */
    public function update(SaveShareQuizRequest $request, ShareQuiz $shareQuiz)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','share_quizzes',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','quizzes',$request->file('featured_image_disabled'));
        }

        if($request->file('featured_image_url')){
            $data['featured_image_url'] = $this->uploadImage('gcs','share_quizzes',$request->file('featured_image_url'));
        }

        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $shareQuiz->featured_image_url = null;
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','share_quizzes',$request->file('game_banner'));
        }

        if (isset(($data['delete_image_holder_2_hidden'])) && $data['delete_image_holder_2_hidden'] == true) {
            $shareQuiz->game_banner = null;
        }

        $shareQuiz->fill($data);
        $shareQuiz->save();

        return redirect(route('sharegames.index', ['tenant' => tenant('id')]))->with('status', trans('Share game saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShareQuiz  $shareQuiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShareQuiz $shareQuiz)
    {
        $shareQuiz->load(['award']);

        if ($shareQuiz->award) {
            $shareQuiz->award->delete();
        }

        $shareQuiz->delete();

        return redirect(route('sharegames.index', ['tenant' => tenant('id')]))->with('status', trans('Share game deleted successful'));
    }
}
