<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Models\MemoryCard;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\SaveMemoryCardRequest;
use App\Traits\UploadImageTrait;

class MemoryGameCardController extends Controller
{
    use UploadImageTrait;
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
        $memory_card = new MemoryCard();

        return view('admin.games.memorygame.createcard', [
            'memory_card'       => $memory_card,
            'memory_quiz_id'    => $request->query('memory_quiz_id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveMemoryCardRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','answers',$request->file('featured_image'));
        }

        $memory_card = MemoryCard::create($data);

        return redirect(route('memorygamecards.edit', ['tenant' => tenant('id'), 'memory_card' => $memory_card]))->with('status', trans('Memory card saved successful'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MemoryCard  $memoryCard
     * @return \Illuminate\Http\Response
     */
    public function show(MemoryCard $memoryCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MemoryCard  $memoryCard
     * @return \Illuminate\Http\Response
     */
    public function edit(MemoryCard $memory_card)
    {
        return view('admin.games.memorygame.createcard', [
            'memory_card'  => $memory_card->load('memory_quiz'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MemoryCard  $memoryCard
     * @return \Illuminate\Http\Response
     */
    public function update($id,SaveMemoryCardRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','answers',$request->file('featured_image'));
        }
        $memory_card = MemoryCard::findOrFail($id);
        $memory_card->fill($data);
        $memory_card->save();

        return redirect(route('memorygamecards.edit', ['tenant' => tenant('id'), 'memory_card' => $memory_card]))->with('status', trans('Card saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MemoryCard  $memoryCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(MemoryCard $memoryCard)
    {
        //
    }
}
