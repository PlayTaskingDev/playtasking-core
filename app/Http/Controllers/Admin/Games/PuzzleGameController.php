<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SavePuzzleRequest;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Models\Puzzle;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;

class PuzzleGameController extends Controller
{
    use UploadImageTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $puzzles = Puzzle::all();

        return view('admin.games.puzzlegame.list', [
            'title'         => 'Panel | ' . trans('Puzzles'),
            'description'   => 'Admin Panel',
            'puzzles'       => $puzzles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $puzzle = new Puzzle();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.puzzlegame.edit', [
            'puzzle'        => $puzzle,
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SavePuzzleRequest $request)
    {
        $data = $request->all();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','puzzles',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','puzzles',$request->file('featured_image_disabled'));
        }

        if($request->file('puzzle_image')){
            $data['puzzle_image'] = $this->uploadImage('gcs','puzzles',$request->file('puzzle_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','puzzles',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','puzzles',$request->file('game_banner'));
        }

        Puzzle::create($data);

        return redirect(route('puzzlegames.index', ['tenant' => tenant('id')]))->with('status', trans('Puzzle saved successful'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Puzzle $puzzle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $puzzle = Puzzle::findOrFail($id);
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.puzzlegame.edit', [
            'puzzle'        => $puzzle->load('award','campaign'),
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id,SavePuzzleRequest $request)
    {
        $data = $request->validated();

        if($request->file('featured_image')){
            $data['featured_image'] = $this->uploadImage('gcs','puzzles',$request->file('featured_image'));
        }

        if($request->file('featured_image_disabled')){
            $data['featured_image_disabled'] = $this->uploadImage('gcs','puzzles',$request->file('featured_image_disabled'));
        }

        if($request->file('puzzle_image')){
            $data['puzzle_image'] = $this->uploadImage('gcs','puzzles',$request->file('puzzle_image'));
        }

        if($request->file('failed_image')){
            $data['failed_image'] = $this->uploadImage('gcs','puzzles',$request->file('failed_image'));
        }

        if($request->file('game_banner')){
            $data['game_banner'] = $this->uploadImage('gcs','puzzles',$request->file('game_banner'));
        }

        $puzzle = Puzzle::findOrFail($id);
        if (isset(($data['delete_image_holder_hidden'])) && $data['delete_image_holder_hidden'] == true) {
            $puzzle->game_banner = null;
        }

        $puzzle->fill($data);
        $puzzle->save();

        return redirect(route('puzzlegames.index', ['tenant' => tenant('id')]))->with('status', trans('Puzzle saved successful'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $puzzle = Puzzle::findOrFail($id);
        $puzzle->delete();

        return redirect(route('puzzlegames.index', ['tenant' => tenant('id')]))->with('status', trans('Puzzle deleted successful'));
    }
}
