<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Models\MemoryQuiz;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\SaveMemoryQuizRequest;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Traits\UploadImageTrait;

class MemoryGameController extends Controller
{
    use UploadImageTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $memory_quizzes = MemoryQuiz::withCount('memory_cards')->get();

        return view('admin.games.memorygame.list', [
            'title'             => 'Panel | ' . trans('Memory Quizzes'),
            'description'       => 'Admin Panel',
            'memory_quizzes'    => $memory_quizzes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $memory_quiz = new MemoryQuiz();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.memorygame.edit', [
            'memory_quiz'   => $memory_quiz,
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
    public function store(SaveMemoryQuizRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','quizzes',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','quizzes',$request->file('featured_image_disabled'));
        }

        if($request->file('back_card_image')){
            $data['back_card_image'] = $this->uploadImage('gcs','quizzes',$request->file('back_card_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','quizzes',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','quizzes',$request->file('game_banner'));
        }

        MemoryQuiz::create($data);

        return redirect(route('memorygames.index', ['tenant' => tenant('id')]))->with('status', trans('Memory quiz saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MemoryQuiz  $memoryQuiz
     * @return \Illuminate\Http\Response
     */
    public function show(MemoryQuiz $memoryQuiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MemoryQuiz  $memoryQuiz
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $memoryQuiz = MemoryQuiz::findOrFail($id);
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.memorygame.edit', [
            'memory_quiz'   => $memoryQuiz->load('memory_cards','award','campaign'),
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MemoryQuiz  $memoryQuiz
     * @return \Illuminate\Http\Response
     */
    public function update($id,SaveMemoryQuizRequest $request)
    {
        $data = $request->validated();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','quizzes',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','quizzes',$request->file('featured_image_disabled'));
        }

        if($request->file('back_card_image')){
            $data['back_card_image'] = $this->uploadImage('gcs','quizzes',$request->file('back_card_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','quizzes',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','quizzes',$request->file('game_banner'));
        }
        
        $memoryQuiz = MemoryQuiz::findOrFail($id);
        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $memoryQuiz->game_banner = null;
        }

        $memoryQuiz->fill($data);
        $memoryQuiz->save();

        return redirect(route('memorygames.index', ['tenant' => tenant('id')]))->with('status', trans('Memory quiz saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MemoryQuiz  $memoryQuiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(MemoryQuiz $memoryQuiz)
    {
        $memoryQuiz->load(['memory_cards','award','coupons']);

        if ($memoryQuiz->memory_cards && $memoryQuiz->memory_cards->isNotEmpty()) {
            foreach ($memoryQuiz->memory_cards as $card) {
                $card->delete();
            }
        }

        if ($memoryQuiz->coupons && $memoryQuiz->coupons->isNotEmpty()) {
            foreach ($memoryQuiz->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($memoryQuiz->award) {
            $memoryQuiz->award->delete();
        }

        $memoryQuiz->delete();

        return redirect(route('memorygames.index', ['tenant' => tenant('id')]))->with('status', trans('Memory quiz deleted successful'));
    }
}
