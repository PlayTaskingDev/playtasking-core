<?php

namespace App\Services\Games;

use App\Models\Quiz;
use App\Models\TriviaResponse;
use App\Models\UserInteraction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TriviaResponseService
{
    /**
     * Determina si TODAS las respuestas
     * seleccionadas son correctas.
     */
    public function evaluate(
        Quiz $quiz,
        array $submittedAnswers
    ): bool {
        $quiz->loadMissing(
            'questions.answers'
        );

        if (
            count($submittedAnswers)
            !== $quiz->questions->count()
        ) {
            return false;
        }

        foreach ($quiz->questions as $question) {

            $answerId =
                $submittedAnswers[
                    $question->id
                ]['answer']
                ?? null;

            if (!$answerId) {
                return false;
            }

            /*
             * Importantísimo:
             * comprobar que la respuesta realmente
             * pertenece a ESTA pregunta.
             */
            $answer = $question
                ->answers
                ->firstWhere(
                    'id',
                    $answerId
                );

            if (!$answer) {
                throw ValidationException::withMessages([
                    'answers' =>
                        'Una de las respuestas seleccionadas no pertenece a la pregunta.',
                ]);
            }

            if (!$answer->is_correct) {
                return false;
            }
        }

        return true;
    }


    /**
     * Guarda el detalle completo del intento.
     */
    public function record(
        Quiz $quiz,
        UserInteraction $interaction,
        array $submittedAnswers = []
    ): void {
        $quiz->loadMissing(
            'questions.answers'
        );

        $rows = [];

        $now = now();

        foreach ($quiz->questions as $question) {

            $answerId =
                $submittedAnswers[
                    $question->id
                ]['answer']
                ?? null;


            $selectedAnswer = null;

            if ($answerId) {

                $selectedAnswer = $question
                    ->answers
                    ->firstWhere(
                        'id',
                        $answerId
                    );

                /*
                 * Evitamos guardar una respuesta
                 * perteneciente a otra pregunta.
                 */
                if (!$selectedAnswer) {

                    throw ValidationException::withMessages([
                        'answers' =>
                            'Una respuesta no pertenece a la pregunta indicada.',
                    ]);
                }
            }


            $rows[] = [

                'id' =>
                    (string) Str::uuid(),

                'user_interaction_id' =>
                    $interaction->id,

                'quiz_id' =>
                    $quiz->id,

                'question_id' =>
                    $question->id,

                'answer_id' =>
                    $selectedAnswer?->id,

                'user_id' =>
                    Auth::id(),

                'was_answered' =>
                    $selectedAnswer !== null,

                'is_correct' =>
                    (bool) (
                        $selectedAnswer?->is_correct
                        ?? false
                    ),

                /*
                 * Snapshot histórico.
                 */
                'question_title' =>
                    $question->title,

                'answer_title' =>
                    $selectedAnswer?->title,

                'created_at' =>
                    $now,

                'updated_at' =>
                    $now,
            ];
        }


        /*
         * Una Trivia de 10 preguntas:
         * un único INSERT de 10 filas.
         */
        if (!empty($rows)) {
            TriviaResponse::insert(
                $rows
            );
        }
    }
}