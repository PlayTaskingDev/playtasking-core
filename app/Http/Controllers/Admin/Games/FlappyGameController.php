<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Models\FlappyGame;
use App\Http\Requests\Panel\SaveFlappyGameRequest;

class FlappyGameController extends Controller
{
   use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flappyGames = FlappyGame::all();

        return view('admin.games.flappygame.list', [
            'title'         => 'Panel | ' . trans('Flappy Games'),
            'description'   => 'Admin Panel',
            'flappyGames'   => $flappyGames
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $flappyGame = new FlappyGame();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.flappygame.edit', [
            'flappyGame'   => $flappyGame,
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveFlappyGameRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','flappyGames',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','flappyGames',$request->file('featured_image_disabled'));
        }

        if($request->file('game_bg_image')){
            $data['game_bg_image'] = $this->uploadImage('gcs','flappyGames',$request->file('game_bg_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','flappyGames',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','flappyGames',$request->file('game_banner'));
        }

        if($request->file('flappy_image')){
            $data['flappy_image'] = $this->uploadImage('gcs','flappyGames',$request->file('flappy_image'));
        }

        FlappyGame::create($data);

        return redirect(route('flappyGames.index', ['tenant' => tenant('id')]))->with('status', trans('Flappy Game saved successful'));
    }

    /**
     * Display the specified resource.
     */
    public function show(FlappyGame $flappyGame)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $flappyGame = FlappyGame::findOrFail($id);
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();
        return view('admin.games.flappygame.edit', [
            'flappyGame'   => $flappyGame->load('award','campaign'),
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id,SaveFlappyGameRequest $request)
    {
        $data = $request->validated();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','flappyGames',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','flappyGames',$request->file('featured_image_disabled'));
        }

        if($request->file('game_bg_image')){
            $data['game_bg_image'] = $this->uploadImage('gcs','flappyGames',$request->file('game_bg_image'));
        }

        if($request->file('game_pipe_image')){
            $data['game_pipe_image'] = $this->uploadImage('gcs','flappyGames',$request->file('game_pipe_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','flappyGames',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','flappyGames',$request->file('game_banner'));
        }

        if($request->file('flappy_image')){
            $data['flappy_image'] = $this->uploadImage('gcs','flappyGames',$request->file('flappy_image'));
        }

        $flappyGame = FlappyGame::findOrFail($id);
        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $flappyGame->game_banner = null;
        }

        $flappyGame->fill($data);
        $flappyGame->save();

        return redirect(route('flappygames.index', ['tenant' => tenant('id')]))->with('status', trans('Catch Game saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $flappyGame = FlappyGame::findOrFail($id);
        $flappyGame->load(['award','coupons']);

        if ($flappyGame->memory_cards && $flappyGame->memory_cards->isNotEmpty()) {
            foreach ($flappyGame->memory_cards as $card) {
                $card->delete();
            }
        }

        if ($flappyGame->coupons && $flappyGame->coupons->isNotEmpty()) {
            foreach ($flappyGame->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($flappyGame->award) {
            $flappyGame->award->delete();
        }

        $flappyGame->delete();

        return redirect(route('flappyGames.index', ['tenant' => tenant('id')]))->with('status', trans('Catch Game deleted successful'));
    }
}
