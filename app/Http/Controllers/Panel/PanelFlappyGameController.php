<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Models\FlappyGame;
use App\Http\Requests\Panel\SaveFlappyGameRequest;

class PanelFlappyGameController extends Controller
{
   use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flappy_games = FlappyGame::all();

        return view('panel.flappy_games.index', [
            'title'         => 'Panel | ' . trans('Flappy Games'),
            'description'   => 'Admin Panel',
            'flappy_games'   => $flappy_games
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $flappy_game = new FlappyGame();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('panel.flappy_games.edit', [
            'flappy_game'   => $flappy_game,
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
            $data['featured_image'] = $this->uploadImage('gcs','flappy_games',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','flappy_games',$request->file('featured_image_disabled'));
        }

        if($request->file('game_bg_image')){
            $data['game_bg_image'] = $this->uploadImage('gcs','flappy_games',$request->file('game_bg_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','flappy_games',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','flappy_games',$request->file('game_banner'));
        }

        if($request->file('basket_image')){
            $data['basket_image'] = $this->uploadImage('gcs','flappy_games',$request->file('basket_image'));
        }

        FlappyGame::create($data);

        return redirect(route('flappy_games.index', ['tenant' => tenant('id')]))->with('status', trans('Flappy Game saved successful'));
    }

    /**
     * Display the specified resource.
     */
    public function show(FlappyGame $flappy_game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FlappyGame $flappy_game)
    {
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('panel.flappy_games.edit', [
            'flappy_game'   => $flappy_game->load('award','campaign'),
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveFlappyGameRequest $request, FlappyGame $flappy_game)
    {
        $data = $request->validated();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','flappy_games',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','flappy_games',$request->file('featured_image_disabled'));
        }

        if($request->file('game_bg_image')){
            $data['game_bg_image'] = $this->uploadImage('gcs','flappy_games',$request->file('game_bg_image'));
        }

        if($request->file('game_pipe_image')){
            $data['game_pipe_image'] = $this->uploadImage('gcs','flappy_games',$request->file('game_pipe_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','flappy_games',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','flappy_games',$request->file('game_banner'));
        }

        if($request->file('flappy_image')){
            $data['flappy_image'] = $this->uploadImage('gcs','flappy_games',$request->file('flappy_image'));
        }

        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $flappy_game->game_banner = null;
        }

        $flappy_game->fill($data);
        $flappy_game->save();

        return redirect(route('flappy_games.index', ['tenant' => tenant('id')]))->with('status', trans('Flappy Game saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FlappyGame $flappy_game)
    {
        $flappy_game->load(['catch_objects','award','coupons']);

        if ($flappy_game->memory_cards && $flappy_game->memory_cards->isNotEmpty()) {
            foreach ($flappy_game->memory_cards as $card) {
                $card->delete();
            }
        }

        if ($flappy_game->coupons && $flappy_game->coupons->isNotEmpty()) {
            foreach ($flappy_game->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($flappy_game->award) {
            $flappy_game->award->delete();
        }

        $flappy_game->delete();

        return redirect(route('flappy_games.index', ['tenant' => tenant('id')]))->with('status', trans('Flappy Game deleted successful'));
    }
}
