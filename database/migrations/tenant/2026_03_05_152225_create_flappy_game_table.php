<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flappy_games', function (Blueprint $table) {
             $table->uuid('id')->primary();

            $table->string('title');
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug');
            $table->integer('max_points'); // Max points achievable
            $table->integer('points_per_pipe'); // Points per pipe passed
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
          
            $table->string('game_bg_image',500); // Background image during the game
            $table->string('game_pipe_image',500);
            $table->string('flappy_image',500); // Image of the flappy bird
            
            $table->string('failed_image',500);
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            
            $table->string('game_banner',500)->nullable();
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color');

            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flappy_game');
    }
};
