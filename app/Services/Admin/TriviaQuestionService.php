<?php

namespace App\Services\Admin;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use App\Traits\UploadImageTrait;
use Illuminate\Http\UploadedFile;

class TriviaQuestionService
{
    use UploadImageTrait;

    public function sync(
        Quiz $quiz,
        array $questions
    ): void {
        foreach ($questions as $questionData) {

            /*
             * Eliminar pregunta existente.
             */
            if (
                !empty($questionData['_delete'])
                && !empty($questionData['id'])
            ) {
                $question = $quiz
                    ->questions()
                    ->whereKey($questionData['id'])
                    ->first();

                if ($question) {
                    $question->answers()->delete();
                    $question->delete();
                }

                continue;
            }

            /*
             * Evitamos crear filas vacías.
             */
            if (blank($questionData['title'] ?? null)) {
                continue;
            }

            $question = $this->saveQuestion(
                $quiz,
                $questionData
            );

            $this->syncAnswers(
                $question,
                $questionData['answers'] ?? [],
                $questionData['correct_answer'] ?? null
            );
        }
    }

    private function saveQuestion(
        Quiz $quiz,
        array $data
    ): Question {
        if (!empty($data['id'])) {

            $question = $quiz
                ->questions()
                ->whereKey($data['id'])
                ->firstOrFail();

        } else {

            $question = new Question();
            $question->quiz_id = $quiz->id;
        }

        $question->title = $data['title'];

        if (
            isset($data['featured_image'])
            && $data['featured_image'] instanceof UploadedFile
        ) {
            $question->featured_image =
                $this->uploadImage(
                    'gcs',
                    'questions',
                    $data['featured_image']
                );
        }

        $question->save();

        return $question;
    }

    private function syncAnswers(
        Question $question,
        array $answers,
        $correctAnswerIndex = null
    ): void {
        /*
         * Si el formulario indicó cuál es la correcta,
         * primero desmarcamos todas.
         */
        if ($correctAnswerIndex !== null) {
            $question
                ->answers()
                ->update([
                    'is_correct' => false,
                ]);
        }

        foreach ($answers as $index => $answerData) {

            /*
             * Eliminar respuesta existente.
             */
            if (
                !empty($answerData['_delete'])
                && !empty($answerData['id'])
            ) {
                $question
                    ->answers()
                    ->whereKey($answerData['id'])
                    ->delete();

                continue;
            }

            if (blank($answerData['title'] ?? null)) {
                continue;
            }

            $answer = $this->saveAnswer(
                $question,
                $answerData
            );

            /*
             * Solamente una respuesta correcta.
             */
            if ($correctAnswerIndex !== null) {

                $answer->is_correct =
                    (string) $correctAnswerIndex
                    ===
                    (string) $index;

                $answer->save();
            }
        }
    }

    private function saveAnswer(
        Question $question,
        array $data
    ): Answer {
        if (!empty($data['id'])) {

            $answer = $question
                ->answers()
                ->whereKey($data['id'])
                ->firstOrFail();

        } else {

            $answer = new Answer();
            $answer->question_id = $question->id;
            $answer->is_correct = false;
        }

        $answer->title = $data['title'];

        if (
            isset($data['featured_image'])
            && $data['featured_image'] instanceof UploadedFile
        ) {
            $answer->featured_image =
                $this->uploadImage(
                    'gcs',
                    'answers',
                    $data['featured_image']
                );
        }

        $answer->save();

        return $answer;
    }
}