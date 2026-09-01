<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Models\FlappyGame;
use App\Http\Requests\Panel\SaveFlappyGameRequest;
use App\Services\Admin\AwardService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\AwardCodeService;

class FlappyGameController extends Controller
{
   use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flappyGames = FlappyGame::all();

        return view('admin.games.flappygame.list', [
            'title'         => 'Panel | ' . trans('Flappy Games'),
            'description'   => 'Admin Panel',
            'flappyGames'   => $flappyGames
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $flappyGame = new FlappyGame();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.flappygame.edit', [
            'flappyGame'   => $flappyGame,
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        SaveFlappyGameRequest $request,
        AwardService $awardService,
        AwardCodeService $awardCodeService
    ) {
        $data = $this->prepareGameData($request);

        $awardData = $this->getAwardData($request);

        $flappyGame = DB::transaction(
            function () use (
                $request,
                $data,
                $awardData,
                $awardService,
                $awardCodeService
            ) {

                $flappyGame = FlappyGame::create($data);

                if ($awardData) {

                    $award = $awardService->saveFor(
                        $flappyGame,
                        $awardData
                    );

                    if (
                        $request->boolean(
                            'generate_award_codes'
                        )
                    ) {
                        $awardCodeService->generate(
                            $award,
                            (int) $request->input(
                                'award_codes_quantity'
                            )
                        );
                    }
                }

                return $flappyGame;
            }
        );

        return redirect()
            ->route('flappygames.edit', [
                'tenant' => tenant('id'),
                'flappygame' => $flappyGame,
            ])
            ->with(
                'status',
                trans('Flappy Game saved successful')
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(FlappyGame $flappyGame)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $flappyGame = FlappyGame::query()
        ->with([
            'campaign',
            'award' => function ($query) {
                $query->withCount([
                    'codes_available',
                    'codes_delivered',
                ]);
            },
        ])
        ->findOrFail($id);
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();
        return view('admin.games.flappygame.edit', [
            'flappyGame' => $flappyGame,
            'campaigns' => $campaigns,
            'content_type' => $content_type,
            'time_slots' => $time_slots,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        $id,
        SaveFlappyGameRequest $request,
        AwardService $awardService,
        AwardCodeService $awardCodeService
    ) {
        $flappyGame =
            FlappyGame::findOrFail($id);

        $data = $this->prepareGameData(
            $request
        );

        $awardData = $this->getAwardData(
            $request
        );

        if (
            $request->boolean(
                'delete_image_holder_hidden'
            )
        ) {
            $data['game_banner'] = null;
        }

        DB::transaction(
            function () use (
                $request,
                $flappyGame,
                $data,
                $awardData,
                $awardService,
                $awardCodeService
            ) {

                $flappyGame->update($data);

                if ($awardData) {

                    $award = $awardService->saveFor(
                        $flappyGame,
                        $awardData
                    );

                    if (
                        $request->boolean(
                            'generate_award_codes'
                        )
                    ) {
                        $awardCodeService->generate(
                            $award,
                            (int) $request->input(
                                'award_codes_quantity'
                            )
                        );
                    }
                }
            }
        );

        return redirect()
            ->route(
                'flappygames.edit',
                [
                    'tenant' => tenant('id'),
                    'flappygame' => $flappyGame,
                ]
            )
            ->with(
                'status',
                trans('Flappy Game saved successful')
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $flappyGame = FlappyGame::findOrFail($id);
        $flappyGame->load(['award','coupons']);

        if ($flappyGame->memory_cards && $flappyGame->memory_cards->isNotEmpty()) {
            foreach ($flappyGame->memory_cards as $card) {
                $card->delete();
            }
        }

        if ($flappyGame->coupons && $flappyGame->coupons->isNotEmpty()) {
            foreach ($flappyGame->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($flappyGame->award) {
            $flappyGame->award->delete();
        }

        $flappyGame->delete();

        return redirect(route('flappyGames.index', ['tenant' => tenant('id')]))->with('status', trans('Catch Game deleted successful'));
    }
    private function prepareGameData(
        SaveFlappyGameRequest $request
    ): array {

        $data = Arr::except(
            $request->validated(),
            [
                'award_title',
                'award_content',
                'generate_award_codes',
                'award_codes_quantity',
                'delete_image_holder_hidden',
            ]
        );

        $imageFields = [
            'featured_image',
            'featured_image_disabled',

            'game_bg_image',
            'game_pipe_image',
            'game_ground_image',

            'flappy_image_animated_1',
            'flappy_image_animated_2',
            'flappy_image_animated_3',

            'failed_image',
            'game_banner',
        ];

        foreach ($imageFields as $field) {

            if (!$request->hasFile($field)) {
                continue;
            }

            $data[$field] = $this->uploadImage(
                'gcs',
                'flappyGames',
                $request->file($field)
            );
        }

        return $data;
    }
    private function getAwardData(
    SaveFlappyGameRequest $request
    ): ?array {

        $title = $request->input('award_title');
        $content = $request->input('award_content');

        if (
            blank($title)
            &&
            blank($content)
        ) {
            return null;
        }

        return [
            'title' => $title,
            'content' => $content,
        ];
    }
}
