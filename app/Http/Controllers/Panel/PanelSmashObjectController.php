<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveSmashObjectRequest;
use App\Models\SmashObject;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;


class PanelSmashObjectController extends Controller
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

        return view('panel.smash_objects.edit', [
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
            $data['object_image'] = $this->uploadImage('gcs','answers',$request->file('object_image'));
        }

        $smash_object = SmashObject::create($data);

        return redirect(route('smash_objects.edit', ['tenant' => tenant('id'), 'smash_object' => $smash_object]))->with('status', trans('Object saved successful'));
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
    public function edit(SmashObject $smash_object)
    {
        return view('panel.smash_objects.edit', [
            'smash_object'  => $smash_object->load('smash_game'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveSmashObjectRequest $request, SmashObject $smash_object)
    {
        $data = $request->all();

        if($request->file('object_image')){
            $data['object_image'] = $this->uploadImage('gcs','smash_objects',$request->file('object_image'));
        }

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
