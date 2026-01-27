<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaElement;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\SaveMediaElementRequest;
use App\Traits\UploadImageTrait;

class ResourcesController extends Controller
{
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $media_elements = MediaElement::orderBy('created_at','desc')->get();

        return view('admin.resources', [
            'title'             => 'Panel | ' . trans('Media elements'),
            'description'       => 'Admin Panel',
            'media_elements'    => $media_elements
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContentType  $contentType
     * @return \Illuminate\Http\Response
     */
    public function show(ContentType $contentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContentType  $contentType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json([
            'data'   => ContentType::find($id)
        ], 200);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContentType  $contentType
     * @return \Illuminate\Http\Response
     */
    public function update($id, SaveContentTypeRequest $request, ContentType $contentType)
    {
        $data = $request->validated();

        if($request->file('icon')){
            $data['icon'] = $this->uploadImage('gcs','settings',$request->file('icon'));
        }

        if($request->file('icon_active')){
            $data['icon_active'] = $this->uploadImage('gcs','settings',$request->file('icon_active'));
        }

        if($request->file('section_banner')){
            $data['section_banner'] = $this->uploadImage('gcs','settings',$request->file('section_banner'));
        }

        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $contentType->section_banner = null;
        }
        
        $contentType = ContentType::findorFail($id);
        $contentType->fill($data);
        $contentType->save();

        return redirect(route('dynamics.index', ['tenant' => tenant('id')]))->with('status', trans('Dynamic Content saved successful'));
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContentType  $contentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContentType $contentType)
    {
        //
    }
}
