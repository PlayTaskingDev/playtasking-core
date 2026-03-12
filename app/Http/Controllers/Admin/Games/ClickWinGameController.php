<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveClickWinRequest;
use App\Models\Campaign;
use App\Models\ClickWin;
use App\Models\ContentType;
use App\Models\ShareQuiz;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;

class ClickWinGameController extends Controller
{
    use UploadImageTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $click_wins = ClickWin::all();

        return view('admin.games.clickwingame.list', [
            'title'             => 'Panel | ' . trans('Click and Win'),
            'description'       => 'Admin Panel',
            'click_wins'        => $click_wins
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $click_win = new ClickWin();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.clickwingame.edit', [
            'click_win'     => $click_win,
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
    public function store(SaveClickWinRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','click_wins',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','click_wins',$request->file('featured_image_disabled'));
        }

        /* if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','click_wins',$request->file('game_banner'));
        } */

        ClickWin::create($data);

        return redirect(route('clickwingames.index', ['tenant' => tenant('id')]))->with('status', trans('Click and win saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShareQuiz  $click_win
     * @return \Illuminate\Http\Response
     */
    public function show(ClickWin $click_win)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShareQuiz  $click_win
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $click_win = ClickWin::findOrFail($id);
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.clickwingame.edit', [
            'click_win'     => $click_win->load('award','campaign'),
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShareQuiz  $click_win
     * @return \Illuminate\Http\Response
     */
    public function update($id,SaveClickWinRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','click_wins',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','click_wins',$request->file('featured_image_disabled'));
        }

        /* if (isset(($data['delete_image_holder_hidden']))) {
            $click_win->featured_image_url = null;
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','click_wins',$request->file('game_banner'));
        } */
        $click_win = ClickWin::findOrFail($id);
        $click_win->fill($data);
        $click_win->save();

        return redirect(route('clickwingames.index', ['tenant' => tenant('id')]))->with('status', trans('Click and win saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShareQuiz  $click_win
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClickWin $click_win)
    {
        $click_win->load(['award']);

        if ($click_win->award) {
            $click_win->award->delete();
        }

        $click_win->delete();

        return redirect(route('clickwingames.index', ['tenant' => tenant('id')]))->with('status', trans('Click and win deleted successful'));
    }
}
