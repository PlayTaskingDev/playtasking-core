<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Panel\SavePageRequest;

class PanelPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::all();

        return view('panel.pages.index', [
            'title'         => 'Panel | ' . trans('Pages'),
            'description'   => 'Admin Panel',
            'pages'         => $pages
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
    public function store(SavePageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('panel.pages.edit', [
            'page' => $page
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(SavePageRequest $request, Page $page)
    {
        $data = $request->all();
        $data['active'] = $request->has('active');

        if($request->file('icon')){
            $data['icon'] = $this->uploadImage('gcs','pages',$request->file('icon'));
        }

        $page->fill($data);
        $page->save();

        Cache::forget('pages');

        Cache::rememberForever('pages', function () {
            return Page::whereActive(true)->orderBy('id','desc')->get();
        });

        return redirect(route('pages.index', ['tenant' => tenant('id')]))->with('status', trans('Page saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        //
    }
}
