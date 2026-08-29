<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveSmashObjectRequest;
use App\Models\SmashObject;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;


class SmashGameObjectController extends Controller
{
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $smash_object = new SmashObject();

        return view('admin.games.smashgame.createsmashobject', [
            'smash_object'       => $smash_object,
            'smash_game_id'      => $request->query('smash_game_id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveSmashObjectRequest $request)
    {
        $data = $request->all();

        if($request->file('object_image')){
            $data['object_image'] = $this->uploadImage(
                                    'gcs',
                                    'smash_objects',
                                    $request->file('object_image')
                                );
        }

        $smash_object = SmashObject::create($data);

        return redirect(route('smashgameobjects.edit', ['tenant' => tenant('id'), 'smash_object' => $smash_object]))->with('status', trans('Object saved successful'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SmashObject $smash_object)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $smash_object = SmashObject::findOrFail($id);
        return view('admin.games.smashgame.createsmashobject', [
            'smash_object'  => $smash_object->load('smash_game'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id,SaveSmashObjectRequest $request)
    {
        $data = $request->all();

        if($request->file('object_image')){
            $data['object_image'] = $this->uploadImage('gcs','smash_objects',$request->file('object_image'));
        }
        $smash_object = SmashObject::findOrFail($id);
        $smash_object->fill($data);
        $smash_object->save();

        return redirect(route('smash_objects.edit', ['tenant' => tenant('id'), 'smash_object' => $smash_object]))->with('status', trans('Object saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SmashObject $smash_object)
    {
        //
    }
}
