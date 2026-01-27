<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\SaveAwardRequest;

class AwardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $award = new Award();
        return view('admin.awards', [
            'award'             => $award,
            'awardable_id'      => $request->query('awardable_id'),
            'awardable_type'    => $request->query('awardable_type'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveAwardRequest $request)
    {
        $data = $request->all();

        $award = Award::create($data);

        return redirect(route('awards.edit', ['tenant' => tenant('id'), 'award' => $award]))->with('status', trans('Award saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function show(Award $award)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function edit($id )
    {
        $award = Award::findOrFail($id);
        return view('admin.awards', [
            'award'  => $award->load('awardable'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function update($id, SaveAwardRequest $request)
    {
        $data = $request->all();
        $award = Award::findOrFail($id);
        $award->fill($data);
        $award->save();

        return redirect(route('v2awards.edit', ['tenant' => tenant('id'), 'v2award' => $award]))->with('status', trans('Award saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function destroy(Award $award)
    {
        //
    }
}
