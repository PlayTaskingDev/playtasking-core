<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MediaElement;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\SaveMediaElementRequest;
use App\Traits\UploadImageTrait;

class PanelMediaElementController extends Controller
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

        return view('panel.media_elements.index', [
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
        $media_element = new MediaElement();

        return view('panel.media_elements.edit', [
            'media_element' => $media_element,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveMediaElementRequest $request)
    {
        $data = $request->all();

        $data['asset'] = $this->uploadImage('gcs','media_elements',$request->file('asset'));
        $data['mime_type'] = $request->file('asset')->getClientMimeType();

        MediaElement::create($data);

        return redirect(route('media_elements.index',['tenant' => tenant('id')]))->with('status', trans('Media element saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MediaElement  $mediaElement
     * @return \Illuminate\Http\Response
     */
    public function show(MediaElement $media_element)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MediaElement  $mediaElement
     * @return \Illuminate\Http\Response
     */
    public function edit(MediaElement $media_element)
    {
        return view('panel.media_elements.edit', [
            'media_element'  => $media_element
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MediaElement  $mediaElement
     * @return \Illuminate\Http\Response
     */
    public function update(SaveMediaElementRequest $request, MediaElement $media_element)
    {
        $data = $request->all();

        if($request->file('asset')){
            $data['asset'] = $this->uploadImage('gcs','media_elements',$request->file('asset'));
        }
        
        $media_element->fill($data);
        $media_element->save();

        return redirect(route('media_elements.index', ['tenant' => tenant('id')]))->with('status', trans('Media element saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaElement  $mediaElement
     * @return \Illuminate\Http\Response
     */
    public function destroy(MediaElement $media_element)
    {
        //
    }
}
