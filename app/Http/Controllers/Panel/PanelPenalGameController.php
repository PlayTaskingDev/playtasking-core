<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Models\PenalGame;
use App\Http\Requests\Panel\SavePenalGameRequest;

class PanelPenalGameController extends Controller
{
   use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penal_games = PenalGame::all();

        return view('panel.penal_games.index', [
            'title'         => 'Panel | ' . trans('Penal Games'),
            'description'   => 'Admin Panel',
            'penal_games'   => $penal_games
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penal_game = new PenalGame();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('panel.penal_games.edit', [
            'penal_game'   => $penal_game,
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SavePenalGameRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','penal_games',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','penal_games',$request->file('featured_image_disabled'));
        }

        if($request->file('game_bg_image')){
            $data['game_bg_image'] = $this->uploadImage('gcs','penal_games',$request->file('game_bg_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','penal_games',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','penal_games',$request->file('game_banner'));
        }

        if($request->file('basket_image')){
            $data['basket_image'] = $this->uploadImage('gcs','penal_games',$request->file('basket_image'));
        }

        PenalGame::create($data);

        return redirect(route('penal_games.index', ['tenant' => tenant('id')]))->with('status', trans('Penal Game saved successful'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PenalGame $penal_game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenalGame $penal_game)
    {
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('panel.penal_games.edit', [
            'penal_game'   => $penal_game->load('award','campaign'),
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SavePenalGameRequest $request, PenalGame $penal_game)
    {
        $data = $request->validated();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','penal_games',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','penal_games',$request->file('featured_image_disabled'));
        }

        if($request->file('game_bg_image')){
            $data['game_bg_image'] = $this->uploadImage('gcs','penal_games',$request->file('game_bg_image'));
        }

        if($request->file('game_pipe_image')){
            $data['game_pipe_image'] = $this->uploadImage('gcs','penal_games',$request->file('game_pipe_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','penal_games',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','penal_games',$request->file('game_banner'));
        }

        if($request->file('flappy_image')){
            $data['flappy_image'] = $this->uploadImage('gcs','penal_games',$request->file('flappy_image'));
        }

        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $penal_game->game_banner = null;
        }

        $penal_game->fill($data);
        $penal_game->save();

        return redirect(route('penal_games.index', ['tenant' => tenant('id')]))->with('status', trans('Penal Game saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenalGame $penal_game)
    {
        $penal_game->load(['award','coupons']);

        if ($penal_game->memory_cards && $penal_game->memory_cards->isNotEmpty()) {
            foreach ($penal_game->memory_cards as $card) {
                $card->delete();
            }
        }

        if ($penal_game->coupons && $penal_game->coupons->isNotEmpty()) {
            foreach ($penal_game->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($penal_game->award) {
            $penal_game->award->delete();
        }

        $penal_game->delete();

        return redirect(route('penal_games.index', ['tenant' => tenant('id')]))->with('status', trans('Penal Game deleted successful'));
    }
}
