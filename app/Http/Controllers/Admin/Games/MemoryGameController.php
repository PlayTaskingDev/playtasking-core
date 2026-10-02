<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Models\MemoryQuiz;
use App\Http\Requests\Panel\SaveMemoryQuizRequest;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Traits\UploadImageTrait;
use App\Services\Admin\MemoryCardService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\AwardService;
use App\Services\Admin\AwardCodeService;

class MemoryGameController extends Controller
{
    use UploadImageTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $memory_quizzes = MemoryQuiz::withCount('memory_cards')->get();

        return view('admin.games.memorygame.list', [
            'title'             => 'Panel | ' . trans('Memory Quizzes'),
            'description'       => 'Admin Panel',
            'memory_quizzes'    => $memory_quizzes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $memory_quiz = new MemoryQuiz();
        $memory_quiz->setRelation(
            'memory_cards',
            collect()
        );

        $memory_quiz->setRelation(
            'award',
            null
        );
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.memorygame.edit', [
            'memory_quiz'   => $memory_quiz,
            'campaigns'     => $campaigns,
            'content_type'  => $content_type,
            'time_slots'    => $time_slots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
    SaveMemoryQuizRequest $request,
    MemoryCardService $memoryCardService,
    AwardService $awardService,
        AwardCodeService $awardCodeService
    ) {
        $validated =
            $request->validated();

        $memoryData =
            $this->prepareMemoryData(
                $request
            );

        $awardData =
            $this->getAwardData(
                $request
            );

        $memoryQuiz = DB::transaction(
            function () use (
                $request,
                $validated,
                $memoryData,
                $awardData,
                $memoryCardService,
                $awardService,
                $awardCodeService
            ) {

                /*
                * 1. Memory
                */
                $memoryQuiz =
                    MemoryQuiz::create(
                        $memoryData
                    );


                /*
                * 2. Cartas
                */
                if (
                    isset($validated['cards'])
                ) {
                    $memoryCardService->sync(
                        $memoryQuiz,
                        $validated['cards']
                    );
                }


                /*
                * 3. Premio
                */
                if ($awardData) {

                    $award =
                        $awardService->saveFor(
                            $memoryQuiz,
                            $awardData
                        );


                    /*
                    * 4. Códigos
                    */
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


                return $memoryQuiz;
            }
        );


        return redirect()
            ->route(
                'memorygames.edit',
                [
                    'tenant' =>
                        tenant('id'),

                    'memorygame' =>
                        $memoryQuiz,
                ]
            )
            ->with(
                'status',
                trans(
                    'Memory quiz saved successful'
                )
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MemoryQuiz  $memoryQuiz
     * @return \Illuminate\Http\Response
     */
    public function show(MemoryQuiz $memoryQuiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MemoryQuiz  $memoryQuiz
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $memoryQuiz = MemoryQuiz::query()
            ->with([
                'memory_cards',
                'campaign',

                'award' => function ($query) {
                    $query->withCount([
                        'codes_available',
                        'codes_delivered',
                    ]);
                },
            ])
            ->findOrFail($id);

        $campaigns =
            Campaign::all();

        $content_type =
            ContentType::where(
                'system_name',
                'games'
            )->first();

        $time_slots =
            get_time_slots();

        return view(
            'admin.games.memorygame.edit',
            [
                'memory_quiz' =>
                    $memoryQuiz,

                'campaigns' =>
                    $campaigns,

                'content_type' =>
                    $content_type,

                'time_slots' =>
                    $time_slots,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MemoryQuiz  $memoryQuiz
     * @return \Illuminate\Http\Response
     */
    public function update(
        $id,
        SaveMemoryQuizRequest $request,
        MemoryCardService $memoryCardService,
        AwardService $awardService,
        AwardCodeService $awardCodeService
    ) {
        $memoryQuiz =
            MemoryQuiz::findOrFail($id);

        $validated =
            $request->validated();

        $memoryData =
            $this->prepareMemoryData(
                $request
            );

        $awardData =
            $this->getAwardData(
                $request
            );


        if (
            $request->boolean(
                'delete_image_holder_hidden'
            )
        ) {
            $memoryData['game_banner'] =
                null;
        }


        DB::transaction(
            function () use (
                $request,
                $memoryQuiz,
                $validated,
                $memoryData,
                $awardData,
                $memoryCardService,
                $awardService,
                $awardCodeService
            ) {

                /*
                * Memory
                */
                $memoryQuiz->update(
                    $memoryData
                );


                /*
                * Cartas
                */
                if (
                    isset($validated['cards'])
                ) {
                    $memoryCardService->sync(
                        $memoryQuiz,
                        $validated['cards']
                    );
                }


                /*
                * Premio
                */
                if ($awardData) {

                    $award =
                        $awardService->saveFor(
                            $memoryQuiz,
                            $awardData
                        );


                    /*
                    * Los códigos solamente se generan
                    * cuando el administrador lo pide.
                    */
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
                'memorygames.edit',
                [
                    'tenant' =>
                        tenant('id'),

                    'memorygame' =>
                        $memoryQuiz,
                ]
            )
            ->with(
                'status',
                trans(
                    'Memory quiz saved successful'
                )
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MemoryQuiz  $memoryQuiz
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $memoryQuiz = MemoryQuiz::findOrFail($id);
        $memoryQuiz->load(['memory_cards','award','coupons']);

        if ($memoryQuiz->memory_cards && $memoryQuiz->memory_cards->isNotEmpty()) {
            foreach ($memoryQuiz->memory_cards as $card) {
                $card->delete();
            }
        }

        if ($memoryQuiz->coupons && $memoryQuiz->coupons->isNotEmpty()) {
            foreach ($memoryQuiz->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($memoryQuiz->award) {
            $memoryQuiz->award->delete();
        }

        $memoryQuiz->delete();

        return redirect(route('memorygames.index', ['tenant' => tenant('id')]))->with('status', trans('Memory quiz deleted successful'));
    }
    private function prepareMemoryData(
        SaveMemoryQuizRequest $request
    ): array {
        $imageFields = [
            'featured_image',
            'featured_image_disabled',
            'back_card_image',
            'failed_image',
            'game_banner',
        ];

        $data = Arr::except(
            $request->validated(),
                array_merge(
                    $imageFields,
                    [
                        'cards',

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
                'quizzes',
                $request->file($field)
            );
        }

        $data['btn_border'] =
            $request->boolean('btn_border');

        $data['btn_shadow'] =
            $request->boolean('btn_shadow');

        return $data;
    }
    private function getAwardData(
        SaveMemoryQuizRequest $request
    ): ?array {
        $title = $request->input(
            'award_title'
        );

        $content = $request->input(
            'award_content'
        );

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
