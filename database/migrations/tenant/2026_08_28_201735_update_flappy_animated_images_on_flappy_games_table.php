<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flappy_games', function (Blueprint $table) {

            // Renombramos la imagen actual
            $table->renameColumn(
                'flappy_image',
                'flappy_image_animated_1'
            );

            // Agregamos las otras dos posiciones del personaje
            $table->string(
                'flappy_image_animated_2',
                500
            )->nullable()->after('flappy_image');

            $table->string(
                'flappy_image_animated_3',
                500
            )->nullable()->after('flappy_image');
        });
    }

    public function down(): void
    {
        Schema::table('flappy_games', function (Blueprint $table) {

            $table->dropColumn([
                'flappy_image_animated_2',
                'flappy_image_animated_3',
            ]);

            $table->renameColumn(
                'flappy_image_animated_1',
                'flappy_image'
            );
        });
    }
};