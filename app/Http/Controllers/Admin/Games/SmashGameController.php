<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\SaveSmashGameRequest;
use App\Models\SmashGame;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Traits\UploadImageTrait;
use App\Services\Admin\AwardService;
use App\Services\Admin\AwardCodeService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SmashGameController extends Controller
{
     use UploadImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $smash_games = SmashGame::all();

        return view('admin.games.smashgame.list', [
            'title'         => 'Panel | ' . trans('Smash Games'),
            'description'   => 'Admin Panel',
            'smash_games'   => $smash_games
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $smash_game = new SmashGame();
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();
        return view('admin.games.smashgame.edit', [
            'smash_game'   => $smash_game,
            'campaigns'    => $campaigns,
            'content_type' => $content_type,
            'time_slots'   => $time_slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        SaveSmashGameRequest $request,
        AwardService $awardService,
        AwardCodeService $awardCodeService
    ) {
        $data = $this->prepareGameData(
            $request
        );

        $awardData = $this->getAwardData(
            $request
        );

        $smashGame = DB::transaction(
            function () use (
                $request,
                $data,
                $awardData,
                $awardService,
                $awardCodeService
            ) {

                $smashGame = SmashGame::create(
                    $data
                );

                if ($awardData) {

                    $award = $awardService->saveFor(
                        $smashGame,
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

                return $smashGame;
            }
        );

        return redirect()
            ->route(
                'smashgames.edit',
                [
                    'tenant' => tenant('id'),
                    'smashgame' => $smashGame,
                ]
            )
            ->with(
                'status',
                trans('Smash Game saved successful')
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(SmashGame $smash_game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $smash_game = SmashGame::query()
            ->with([
                'campaign',
                'smash_objects',

                'award' => function ($query) {

                    $query->withCount([
                        'codes_available',
                        'codes_delivered',
                    ]);
                },
            ])
            ->findOrFail($id);

        $campaigns = Campaign::all();

        $content_type = ContentType::where(
            'system_name',
            'games'
        )->first();

        $time_slots = get_time_slots();

        return view(
            'admin.games.smashgame.edit',
            [
                'smash_game' => $smash_game,
                'campaigns' => $campaigns,
                'content_type' => $content_type,
                'time_slots' => $time_slots,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        $id,
        SaveSmashGameRequest $request,
        AwardService $awardService,
        AwardCodeService $awardCodeService
    ) {
        $smashGame = SmashGame::findOrFail(
            $id
        );

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
                $smashGame,
                $data,
                $awardData,
                $awardService,
                $awardCodeService
            ) {

                $smashGame->update(
                    $data
                );

                if ($awardData) {

                    $award = $awardService->saveFor(
                        $smashGame,
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
                'smashgames.edit',
                [
                    'tenant' => tenant('id'),
                    'smashgame' => $smashGame,
                ]
            )
            ->with(
                'status',
                trans('Smash Game saved successful')
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $smash_game = SmashGame::findOrFail($id);
        $smash_game->load(['smash_objects','award','coupons']);

        if ($smash_game->smash_objects && $smash_game->smash_objects->isNotEmpty()) {
            foreach ($smash_game->smash_objects as $object) {
                $object->delete();
            }
        }

        if ($smash_game->coupons && $smash_game->coupons->isNotEmpty()) {
            foreach ($smash_game->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($smash_game->award) {
            $smash_game->award->delete();
        }

        $smash_game->delete();

        return redirect(route('smashgames.index', ['tenant' => tenant('id')]))->with('status', trans('Smash Game deleted successful'));
    }
    private function prepareGameData(
        SaveSmashGameRequest $request
    ): array {

        $imageFields = [
            'featured_image',
            'featured_image_disabled',
            'game_bg_image',
            'failed_image',
            'game_banner',
        ];

        $data = Arr::except(
            $request->validated(),
            array_merge(
                $imageFields,
                [
                    'award_title',
                    'award_content',
                    'generate_award_codes',
                    'award_codes_quantity',
                    'delete_image_holder_hidden',
                ]
            )
        );

        foreach ($imageFields as $field) {

            if (!$request->hasFile($field)) {
                continue;
            }

            $data[$field] = $this->uploadImage(
                'gcs',
                'smash_games',
                $request->file($field)
            );
        }

        return $data;
    }
    private function getAwardData(
        SaveSmashGameRequest $request
    ): ?array {

        $title = $request->input('award_title');
        $content = $request->input('award_content');

        if (
            blank($title) &&
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
