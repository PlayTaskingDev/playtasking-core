<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveCatchObjectRequest;
use App\Models\CatchObject;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;

class CatchGameObjectController extends Controller
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
        $catch_object = new CatchObject();

        return view('admin.games.catchgame.createobject', [
            'catch_object'       => $catch_object,
            'catch_game_id'      => $request->query('catch_game_id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveCatchObjectRequest $request)
    {
        $data = $request->all();

        if($request->file('object_image')){
            $data['object_image'] = $this->uploadImage('gcs','answers',$request->file('object_image'));
        }

        $catch_object = CatchObject::create($data);

        return redirect(route('catchgameobjects.edit', ['tenant' => tenant('id'), 'catchgameobject' => $catch_object]))->with('status', trans('Object saved successful'));
    }

    /**
     * Display the specified resource.
     */
    public function show(CatchObject $catch_object)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $catch_object = CatchObject::findOrFail($id);
        return view('admin.games.catchgame.createobject', [
            'catch_object'  => $catch_object->load('catch_game'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id,SaveCatchObjectRequest $request)
    {
        $data = $request->all();

        if($request->file('object_image')){
            $data['object_image'] = $this->uploadImage('gcs','catchgameobjects',$request->file('object_image'));
        }
        $catch_object = CatchObject::findOrFail($id);
        $catch_object->fill($data);
        $catch_object->save();

        return redirect(route('catchgameobjects.edit', ['tenant' => tenant('id'), 'catchgameobject' => $catch_object]))->with('status', trans('Object saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CatchObject $catch_object)
    {
        //
    }
}
