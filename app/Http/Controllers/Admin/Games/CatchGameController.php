<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveCatchGameRequest;
use App\Models\CatchGame;
use App\Models\Campaign;
use App\Models\ContentType;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;

class CatchGameController extends Controller
{
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $catch_games = CatchGame::all();

        return view('admin.games.catchgame.list', [
            'title'         => 'Panel | ' . trans('Catch Games'),
            'description'   => 'Admin Panel',
            'catch_games'   => $catch_games
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $catch_game = new CatchGame();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.catchgame.edit', [
            'catch_game'   => $catch_game,
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveCatchGameRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','catch_games',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','catch_games',$request->file('featured_image_disabled'));
        }

        if($request->file('game_bg_image')){
            $data['game_bg_image'] = $this->uploadImage('gcs','catch_games',$request->file('game_bg_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','catch_games',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','catch_games',$request->file('game_banner'));
        }

        if($request->file('basket_image')){
            $data['basket_image'] = $this->uploadImage('gcs','catch_games',$request->file('basket_image'));
        }

        CatchGame::create($data);

        return redirect(route('catchgames.index', ['tenant' => tenant('id')]))->with('status', trans('Catch Game saved successful'));
    }

    /**
     * Display the specified resource.
     */
    public function show(CatchGame $catch_game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $catch_game = CatchGame::findOrFail($id);
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.catchgame.edit', [
            'catch_game'   => $catch_game->load('catch_objects'),
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, SaveCatchGameRequest $request)
    {
        $data = $request->validated();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','catch_games',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','catch_games',$request->file('featured_image_disabled'));
        }

        if($request->file('game_bg_image')){
            $data['game_bg_image'] = $this->uploadImage('gcs','catch_games',$request->file('game_bg_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','catch_games',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','catch_games',$request->file('game_banner'));
        }

        if($request->file('basket_image')){
            $data['basket_image'] = $this->uploadImage('gcs','catch_games',$request->file('basket_image'));
        }

        $catch_game = CatchGame::findorFail($id);
        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $catch_game->game_banner = null;
        }

        $catch_game->fill($data);
        $catch_game->save();

        return redirect(route('catch_games.index', ['tenant' => tenant('id')]))->with('status', trans('Catch Game saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $catch_game = CatchGame::findorFail($id);
        $catch_game->load(['catch_objects','award','coupons']);

        if ($catch_game->memory_cards && $catch_game->memory_cards->isNotEmpty()) {
            foreach ($catch_game->memory_cards as $card) {
                $card->delete();
            }
        }

        if ($catch_game->coupons && $catch_game->coupons->isNotEmpty()) {
            foreach ($catch_game->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($catch_game->award) {
            $catch_game->award->delete();
        }

        $catch_game->delete();

        return redirect(route('catch_games.index', ['tenant' => tenant('id')]))->with('status', trans('Catch Game deleted successful'));
    }
}
