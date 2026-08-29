<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flappy_games', function (Blueprint $table) {

            $table->string('game_ground_image', 500)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('flappy_games', function (Blueprint $table) {

            $table->dropColumn([
                'game_ground_image',
            ]);
        });
    }
};