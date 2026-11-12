<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Traits\CheckParticipationTrait;
use App\Models\AwardCode;
use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\UserInteraction;
use App\Traits\CampaignsTrait;
use App\Services\Games\TriviaResponseService;

class QuizController extends Controller
{
    use CheckParticipationTrait, CampaignsTrait;

    public function quiz_index()
    {
        return redirect()->route('campaign.splash', ['tenant' => tenant('id')]);
    }

    public function quiz_show($slug)
    {
        $today = Carbon::now()->toDateTimeString();
        $quiz = Quiz::with(['questions.answers' => function($q){
            $q->inRandomOrder();
        }, 'campaign'])->where(
            [
                ['slug', $slug],
                ['init_date','<',$today],
                ['end_date','>',$today]
            ])->firstOrFail();

        // Check if user has been participated and won
        $has_paticipated = $this->check_participation($model_id = $quiz->id,$model_type = 'App\Models\Quiz',$user_id = Auth::user()->id,$hit = true);
        if (!is_null($has_paticipated)) {
            return redirect(route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $quiz->award]));
        }

        // Check if user has been participated and failed
        $has_paticipated = $this->check_participation($model_id = $quiz->id,$model_type = 'App\Models\Quiz',$user_id = Auth::user()->id);
        if (!is_null($has_paticipated)) {
            return redirect()->route('game.failed', [
                'tenant' => tenant('id'), 
                'model_type' =>  $quiz->award->model_type, 
                'model' => $quiz
            ]);
        }
        if (!$quiz->enable_chronometer && !session()->has('game_start')) {
            session()->put(
                'game_start',
                Carbon::now()->format(
                    'Y-m-d H:i:s.u'
                )
            );
        }
        $campaign_games = $this->has_content_type($quiz->campaign->id, 'games');
        $campaign_tickets = $this->has_content_type($quiz->campaign->id, 'tickets');
        $campaign_coupons = $this->has_content_type($quiz->campaign->id, 'coupons');

        return view('dashboard.quizzes.show', [
            'quiz'              => $quiz,
            'campaign_games'    => $campaign_games,
            'campaign_tickets'  => $campaign_tickets,
            'campaign_coupons'  => $campaign_coupons,
        ]);
    }

    public function quiz_evaluate(
    Request $request,
    TriviaResponseService $triviaResponseService
) {
    /*
     * 1. Validar respuestas recibidas.
     */
    $rules = [
        'answers' => [
            'required',
            'array',
        ],

        'answers.*.answer' => [
            'required',
            'uuid',
            'exists:answers,id',
        ],

        'quid' => [
            'required',
            'uuid',
            'exists:quizzes,id',
        ],
    ];

    $validator = validator(
        $request->all(),
        $rules
    );

    if ($validator->fails()) {
        return back()
            ->with('status', 'error')
            ->withErrors($validator)
            ->withInput();
    }

    $data = $validator->validated();


    /*
     * 2. Cargar Trivia junto con preguntas,
     * respuestas y premio.
     */
    $quiz = Quiz::query()
        ->with([
            'questions.answers',
            'award',
        ])
        ->findOrFail(
            $data['quid']
        );


    /*
     * 3. Verificar si el usuario ya ganó.
     */
    $has_participated = $this->check_participation(
        model_id: $quiz->id,
        model_type: 'App\Models\Quiz',
        user_id: Auth::id(),
        hit: true
    );

    if (!is_null($has_participated)) {

        session()->forget('game_start');
        session()->forget('game_duration');

        return redirect(
            route(
                'dashboard.awards.show',
                [
                    'tenant' => tenant('id'),
                    'award' => $quiz->award,
                ]
            )
        );
    }


    /*
     * 4. Verificar si el usuario ya participó
     * y perdió.
     */
    $has_participated = $this->check_participation(
        model_id: $quiz->id,
        model_type: 'App\Models\Quiz',
        user_id: Auth::id()
    );

    if (!is_null($has_participated)) {

        session()->forget('game_start');
        session()->forget('game_duration');

        return redirect()
            ->route(
                'game.failed',
                [
                    'tenant' => tenant('id'),
                    'model_type' => $quiz->award->model_type,
                    'model' => $quiz,
                ]
            );
    }


    /*
     * 5. Evaluar las respuestas.
     *
     * El service comprueba:
     *
     * - que cada respuesta pertenezca a su pregunta;
     * - que todas las preguntas hayan sido respondidas;
     * - si todas las respuestas son correctas.
     */
    $hit = $triviaResponseService->evaluate(
        $quiz,
        $data['answers']
    );


    /*
     * 6. Crear UserInteraction y guardar
     * el detalle pregunta por pregunta.
     */
    $userInteraction = DB::transaction(
        function () use (
            $data,
            $quiz,
            $hit,
            $triviaResponseService
        ) {

            /*
             * Interacción general de la Trivia.
             */
            $interaction = $this->attach_user_to_quiz(
                $data,
                $quiz,
                $hit
            );


            /*
             * IMPORTANTE:
             *
             * El attach_user_to_quiz() actual
             * no guarda hit en user_interactions.
             *
             * Lo dejamos explícitamente aquí.
             */
            $interaction->hit = $hit;
            $interaction->save();


            /*
             * Guardar una fila por cada pregunta:
             *
             * question_id
             * answer_id
             * is_correct
             * user_id
             * snapshots
             */
            $triviaResponseService->record(
                $quiz,
                $interaction,
                $data['answers']
            );


            return $interaction;
        }
    );


    /*
     * 7. Limpiar cronómetro después de haber
     * registrado correctamente el intento.
     */
    session()->forget('game_start');
    session()->forget('game_duration');


    /*
     * 8. Si todas las respuestas fueron
     * correctas, entregar un código.
     */
    if ($hit === true) {

        /*
         * Usamos lockForUpdate porque dos usuarios
         * podrían intentar obtener el mismo código
         * simultáneamente.
         */
        $awardCode = DB::transaction(
            function () use (
                $quiz,
                $userInteraction
            ) {

                $awardCode = AwardCode::query()
                    ->where(
                        'award_id',
                        $quiz->award->id
                    )
                    ->where(
                        'active',
                        false
                    )
                    ->lockForUpdate()
                    ->first();


                /*
                 * Ya no quedan códigos.
                 */
                if (!$awardCode) {
                    return null;
                }


                /*
                 * Asignar código al usuario.
                 */
                $awardCode->active = true;
                $awardCode->user_id = Auth::id();
                $awardCode->save();


                /*
                 * Guardar también el código
                 * entregado en user_interactions.
                 */
                $userInteraction->code =
                    $awardCode->code;

                $userInteraction->hit = true;

                $userInteraction->save();


                return $awardCode;
            }
        );


        /*
         * Ganó, pero ya no había códigos.
         */
        if (!$awardCode) {

            return redirect()
                ->route(
                    'game.out_of_coupons',
                    [
                        'tenant' => tenant('id'),
                    ]
                );
        }


        /*
         * Premio entregado correctamente.
         */
        return redirect(
            route(
                'dashboard.awards.show',
                [
                    'tenant' => tenant('id'),
                    'award' => $quiz->award,
                ]
            )
        );
    }


    /*
     * 9. Alguna respuesta fue incorrecta.
     */
    return redirect()
        ->route(
            'game.failed',
            [
                'tenant' => tenant('id'),
                'model_type' => $quiz->award->model_type,
                'model' => $quiz,
            ]
        );
}

    public function quiz_timer_out(Request $request){
        $quiz = Quiz::whereId($request->input('quid'))->with('award')->first();
        $this->attach_user_to_quiz($request->all(), $quiz, false);
        session()->forget('game_start');
        session()->forget('game_duration');
        return redirect()->route('game.failed', [
                'tenant' => tenant('id'), 
                'model_type' =>  $quiz->award->model_type, 
                'model' => $quiz,
                'out_time' => true
            ]);
    }

    public function attach_user_to_quiz($data, $quiz, $hit){
        DB::table('award_user')->insert([
            'model_id'      => $data['quid'],
            'award_id'     => $quiz->award->id,
            'user_id'       => Auth::user()->id,
            'model_type'    => 'App\Models\Quiz',
            'hit'           => $hit,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        $user_interaction = UserInteraction::create([
            'model_id' => $quiz->id,
            'model_title' => $quiz->title,
            'user_id' => Auth::user()->id,
            'hit_created_at' => session('game_start'),
            'hit_updated_at' => Carbon::now()->format('Y-m-d H:i:s.u'),
        ]);
        return $user_interaction;
    }
}
