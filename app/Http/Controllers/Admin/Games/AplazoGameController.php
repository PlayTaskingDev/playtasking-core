<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveAplazoGameRequest;
use App\Models\AplazoGame;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class AplazoGameController extends Controller
{
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aplazo_games = AplazoGame::all();

        return view('admin.games.aplazogame.list', [
            'title'             => 'Panel | ' . trans('Aplazo games'),
            'description'       => 'Admin Panel',
            'aplazo_games'      => $aplazo_games
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $aplazo_game = new AplazoGame();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('panel.aplazo_games.edit', [
            'aplazo_game'   => $aplazo_game->load('award','campaign'),
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveAplazoGameRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','aplazo_games',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','aplazo_games',$request->file('featured_image_disabled'));
        }

        if($request->file('promo_image')){
            $data['promo_image'] = $this->uploadImage('gcs','aplazo_games',$request->file('promo_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','aplazo_games',$request->file('game_banner'));
        }

        AplazoGame::create($data);

        return redirect(route('admin.games.aplazogame.list', ['tenant' => tenant('id')]))->with('status', trans('Aplazo game saved successful'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AplazoGame $aplazo_game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $aplazo_game = AplazoGame::findOrFail($id);
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();
         return view('admin.games.aplazogame.edit', [
            'aplazo_game'   => $aplazo_game->load('award','campaign'),
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, SaveAplazoGameRequest $request)
    {
        $data = $request->all();

        $aplazo_game = AplazoGame::findOrFail($id);

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','aplazo_games',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','aplazo_games',$request->file('featured_image_disabled'));
        }

        if($request->file('promo_image')){
            $data['promo_image'] = $this->uploadImage('gcs','aplazo_games',$request->file('promo_image'));
        }

        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $aplazo_game->game_banner = null;
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','aplazo_games',$request->file('game_banner'));
        }

        if( !$request->has('btn_border') ){
            $data['btn_border'] = false;
        }

        if( !$request->has('btn_shadow') ){
            $data['btn_shadow'] = false;
        }

        $aplazo_game->fill($data);
        $aplazo_game->save();

        return redirect(route('aplazogames.edit', ['tenant' => tenant('id'), 'aplazogame' => $aplazo_game]))->with('status', trans('Aplazo game saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AplazoGame $aplazo_game)
    {
        //
    }
}
