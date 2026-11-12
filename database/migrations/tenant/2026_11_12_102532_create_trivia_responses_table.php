<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trivia_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();

            /*
             * Intento global.
             */
            $table->uuid('user_interaction_id');

            /*
             * Datos del juego.
             */
            $table->uuid('quiz_id');
            $table->uuid('question_id');

            /*
             * Puede ser null en caso de timeout
             * sin haber contestado esa pregunta.
             */
            $table->uuid('answer_id')->nullable();

            /*
             * Lo dejamos directo también para BI.
             */
            $table->uuid('user_id')->nullable();

            $table->boolean('was_answered')
                ->default(false);

            $table->boolean('is_correct')
                ->default(false);

            /*
             * Snapshot.
             *
             * Esto es importante porque una pregunta
             * o respuesta podría editarse después.
             */
            $table->text('question_title')->nullable();
            $table->text('answer_title')->nullable();

            $table->timestamps();

            $table->index([
                'quiz_id',
                'question_id',
            ]);

            $table->index([
                'user_id',
                'quiz_id',
            ]);

            /*
             * Una sola respuesta por pregunta
             * dentro del mismo intento.
             */
            $table->unique(
                [
                    'user_interaction_id',
                    'question_id',
                ],
                'trivia_response_attempt_question_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'trivia_responses'
        );
    }
};