<?php

namespace App\Http\Controllers\Admin\Games;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Http\Requests\Panel\SaveQuizRequest;
use App\Models\Campaign;
use App\Models\ContentType;
use App\Traits\UploadImageTrait;
use App\Services\Admin\TriviaQuestionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\AwardService;
use App\Services\Admin\AwardCodeService;

class TriviaGameController extends Controller
{
    use UploadImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = Quiz::withCount('questions')->orderBy('created_at','desc')->get();

        return view('admin.games.triviagame.list', [
            'title'         => 'Panel | ' . trans('Quizzes'),
            'description'   => 'Admin Panel',
            'quizzes'       => $quizzes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quiz = new Quiz();
        $quiz->setRelation(
            'questions',
            collect()
        );
        $quiz->setRelation(
            'award',
            null
        );
        $campaigns = Campaign::all();
        $content_type = ContentType::where('system_name','games')->first();
        $time_slots = get_time_slots();

        return view('admin.games.triviagame.edit', [
            'quiz'          => $quiz,
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
        SaveQuizRequest $request,
        TriviaQuestionService $triviaQuestionService,
        AwardService $awardService,
        AwardCodeService $awardCodeService
    ) {
        $validated =
            $request->validated();

        $quizData =
            $this->prepareQuizData(
                $request
            );

        $awardData =
            $this->getAwardData(
                $request
            );

        $quiz = DB::transaction(
            function () use (
                $request,
                $validated,
                $quizData,
                $awardData,
                $triviaQuestionService,
                $awardService,
                $awardCodeService
            ) {

                /*
                * 1. Crear Trivia
                */
                $quiz = Quiz::create(
                    $quizData
                );


                /*
                * 2. Preguntas y respuestas
                */
                if (
                    isset(
                        $validated['questions']
                    )
                ) {
                    $triviaQuestionService
                        ->sync(
                            $quiz,
                            $validated['questions']
                        );
                }


                /*
                * 3. Premio
                */
                if ($awardData) {

                    $award =
                        $awardService->saveFor(
                            $quiz,
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
                        $awardCodeService
                            ->generate(
                                $award,
                                (int) $request->input(
                                    'award_codes_quantity'
                                )
                            );
                    }
                }


                return $quiz;
            }
        );


        return redirect()
            ->route(
                'triviagames.edit',
                [
                    'tenant' =>
                        tenant('id'),

                    'triviagame' =>
                        $quiz,
                ]
            )
            ->with(
                'status',
                trans(
                    'Quiz saved successful'
                )
            );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show(Quiz $quiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quiz = Quiz::query()
        ->with([
            'campaign',

            'questions.answers',

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
            'admin.games.triviagame.edit',
            [
                'quiz' => $quiz,
                'campaigns' => $campaigns,
                'content_type' => $content_type,
                'time_slots' => $time_slots,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(
    $id,
    SaveQuizRequest $request,
    TriviaQuestionService $triviaQuestionService,
    AwardService $awardService,
        AwardCodeService $awardCodeService
    ) {
        $quiz = Quiz::findOrFail(
            $id
        );

        $validated =
            $request->validated();

        $quizData =
            $this->prepareQuizData(
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
            $quizData['game_banner'] =
                null;
        }


        DB::transaction(
            function () use (
                $request,
                $quiz,
                $validated,
                $quizData,
                $awardData,
                $triviaQuestionService,
                $awardService,
                $awardCodeService
            ) {

                /*
                * Trivia
                */
                $quiz->update(
                    $quizData
                );


                /*
                * Preguntas
                */
                if (
                    isset(
                        $validated['questions']
                    )
                ) {
                    $triviaQuestionService
                        ->sync(
                            $quiz,
                            $validated['questions']
                        );
                }


                /*
                * Premio
                */
                if ($awardData) {

                    $award =
                        $awardService->saveFor(
                            $quiz,
                            $awardData
                        );


                    /*
                    * Generar códigos solamente
                    * si se marcó explícitamente.
                    */
                    if (
                        $request->boolean(
                            'generate_award_codes'
                        )
                    ) {
                        $awardCodeService
                            ->generate(
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
                'triviagames.edit',
                [
                    'tenant' =>
                        tenant('id'),

                    'triviagame' =>
                        $quiz,
                ]
            )
            ->with(
                'status',
                trans(
                    'Quiz saved successful'
                )
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->load(['questions','answers','award','coupons']);

        if ($quiz->answers && $quiz->answers->isNotEmpty()) {
            foreach ($quiz->answers as $answer) {
                $answer->delete();
            }
        }

        if ($quiz->questions && $quiz->questions->isNotEmpty()) {
            foreach ($quiz->questions as $question) {
                $question->delete();
            }
        }

        if ($quiz->coupons && $quiz->coupons->isNotEmpty()) {
            foreach ($quiz->coupons as $coupon) {
                $coupon->delete();
            }
        }

        if ($quiz->award) {
            $quiz->award->delete();
        }

        $quiz->delete();

        return redirect(route('triviagames.index', ['tenant' => tenant('id')]))->with('status', trans('Quiz deleted successful'));
    }
    private function prepareQuizData(
        SaveQuizRequest $request
    ): array {
        $imageFields = [
            'featured_image',
            'featured_image_disabled',
            'failed_image',
            'failed_image_out_time',
            'game_banner',
        ];

        $data = Arr::except(
            $request->validated(),
            array_merge(
                $imageFields,
                [
                    'questions',

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

        /*
        * Los checkboxes que no vienen en el request
        * significan false.
        */
        $chronometerEnabled =
        $request->boolean(
            'enable_chronometer'
        );

        $data['enable_chronometer'] =
            $chronometerEnabled;

        $data['seconds'] =
            $chronometerEnabled
                ? $request->input('seconds')
                : null;

        $data['btn_border'] =
            $request->boolean('btn_border');

        $data['btn_shadow'] =
            $request->boolean('btn_shadow');

        return $data;
    }
    private function getAwardData(
        SaveQuizRequest $request
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
