<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveSmashGameRequest;
use App\Models\SmashGame;
use App\Models\Campaign;
use App\Models\ContentType;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;

class SmashGameController extends Controller
{
     use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $smash_games = SmashGame::all();

        return view('admin.games.smashgame.list', [
            'title'         => 'Panel | ' . trans('Smash Games'),
            'description'   => 'Admin Panel',
            'smash_games'   => $smash_games
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $smash_game = new SmashGame();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();
        return view('admin.games.smashgame.edit', [
            'smash_game'   => $smash_game,
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveSmashGameRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','smash_games',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','smash_games',$request->file('featured_image_disabled'));
        }

        if($request->file('game_bg_image')){
            $data['game_bg_image'] = $this->uploadImage('gcs','smash_games',$request->file('game_bg_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','smash_games',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','smash_games',$request->file('game_banner'));
        }

        SmashGame::create($data);

        return redirect(route('smashgames.index', ['tenant' => tenant('id')]))->with('status', trans('Smash Game saved successful'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SmashGame $smash_game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $smash_game = SmashGame::findOrFail($id);
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.smashgame.edit', [
            'smash_game'   => $smash_game->load('smash_objects'),
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveSmashGameRequest $request, SmashGame $smash_game)
    {
        dd($request);
        $data = $request->validated();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','smash_games',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','smash_games',$request->file('featured_image_disabled'));
        }

        if($request->file('game_bg_image')){
            $data['game_bg_image'] = $this->uploadImage('gcs','smash_games',$request->file('game_bg_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','smash_games',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','smash_games',$request->file('game_banner'));
        }

        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $smash_game->game_banner = null;
        }

        $smash_game->fill($data);
        $smash_game->save();

        return redirect(route('smashgames.index', ['tenant' => tenant('id')]))->with('status', trans('Smash Game saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SmashGame $smash_game)
    {
        $smash_game->load(['smash_objects','award','coupons']);

        if ($smash_game->memory_cards && $smash_game->memory_cards->isNotEmpty()) {
            foreach ($smash_game->memory_cards as $card) {
                $card->delete();
            }
        }

        if ($smash_game->coupons && $smash_game->coupons->isNotEmpty()) {
            foreach ($smash_game->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($smash_game->award) {
            $smash_game->award->delete();
        }

        $smash_game->delete();

        return redirect(route('smashgames.index', ['tenant' => tenant('id')]))->with('status', trans('Smash Game deleted successful'));
    }
}
