<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Models\PenalGame;
use App\Http\Requests\Panel\SavePenalGameRequest;

class PenalGameController extends Controller
{
   use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penalGames = PenalGame::all();

        return view('admin.games.penalgame.list', [
            'title'         => 'Panel | ' . trans('Penal Games'),
            'description'   => 'Admin Panel',
            'penalGames'   => $penalGames
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penalGame = new PenalGame();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.penalgame.edit', [
            'penalGame'   => $penalGame,
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
            $data['featured_image'] = $this->uploadImage('gcs','penalGames',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','penalGames',$request->file('featured_image_disabled'));
        }

        if ($request->file('game_bg_image_desktop')) {
            $data['game_bg_image_desktop'] = $this->uploadImage(
                'gcs',
                'penalGames',
                $request->file('game_bg_image_desktop')
            );
        }

        if ($request->file('game_bg_image_movil')) {
            $data['game_bg_image_movil'] = $this->uploadImage(
                'gcs',
                'penalGames',
                $request->file('game_bg_image_movil')
            );
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','penalGames',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','penalGames',$request->file('game_banner'));
        }


        PenalGame::create($data);

        return redirect(route('penalgames.index', ['tenant' => tenant('id')]))->with('status', trans('Penal Game saved successful'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PenalGame $penalGame)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penalGame = PenalGame::findOrFail($id);
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();
        return view('admin.games.penalgame.edit', [
            'penalGame'   => $penalGame->load('award','campaign'),
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id,SavePenalGameRequest $request)
    {
        $data = $request->validated();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','penalGames',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','penalGames',$request->file('featured_image_disabled'));
        }

        if ($request->file('game_bg_image_desktop')) {
            $data['game_bg_image_desktop'] = $this->uploadImage(
                'gcs',
                'penalGames',
                $request->file('game_bg_image_desktop')
            );
        }

        if ($request->file('game_bg_image_movil')) {
            $data['game_bg_image_movil'] = $this->uploadImage(
                'gcs',
                'penalGames',
                $request->file('game_bg_image_movil')
            );
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','penalGames',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','penalGames',$request->file('game_banner'));
        }


        $penalGame = PenalGame::findOrFail($id);
        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $penalGame->game_banner = null;
        }

        $penalGame->fill($data);
        $penalGame->save();

        return redirect(route('penalgames.index', ['tenant' => tenant('id')]))->with('status', trans('Catch Game saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penalGame = PenalGame::findOrFail($id);
        $penalGame->load(['award','coupons']);

        if ($penalGame->memory_cards && $penalGame->memory_cards->isNotEmpty()) {
            foreach ($penalGame->memory_cards as $card) {
                $card->delete();
            }
        }

        if ($penalGame->coupons && $penalGame->coupons->isNotEmpty()) {
            foreach ($penalGame->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($penalGame->award) {
            $penalGame->award->delete();
        }

        $penalGame->delete();

        return redirect(route('penalGames.index', ['tenant' => tenant('id')]))->with('status', trans('Catch Game deleted successful'));
    }
}
