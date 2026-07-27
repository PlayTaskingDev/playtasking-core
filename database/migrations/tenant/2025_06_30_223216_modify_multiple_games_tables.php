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
        Schema::table('content_types', function (Blueprint $table) {
            $table->string('game_banner_url',500)->nullable()->after('section_banner');
            $table->string('game_banner_video',500)->nullable()->after('game_banner_url');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('game_banner_url',500)->nullable()->after('game_banner');
            $table->string('game_banner_video',500)->nullable()->after('game_banner_url');
            $table->string('btn_background_color_1')->nullable()->after('game_banner_video');
            $table->string('btn_background_color_2')->nullable()->after('btn_background_color_1');
            $table->string('btn_border_color')->nullable()->after('btn_background_color_2');
            $table->boolean('btn_border')->default(false)->after('btn_border_color');
            $table->string('btn_text_active')->default('Jugar ahora')->after('btn_border');
            $table->string('btn_text_inactive')->default('Ver resultado')->after('btn_text_active');
            $table->boolean('btn_shadow')->default(false)->after('btn_text_inactive');
            $table->string('btn_text_color')->after('btn_shadow');
        });

        Schema::table('memory_quizzes', function (Blueprint $table) {
            $table->string('game_banner_url',500)->nullable()->after('game_banner');
            $table->string('game_banner_video',500)->nullable()->after('game_banner_url');
            $table->string('btn_background_color_1')->nullable()->after('game_banner_video');
            $table->string('btn_background_color_2')->nullable()->after('btn_background_color_1');
            $table->string('btn_border_color')->nullable()->after('btn_background_color_2');
            $table->boolean('btn_border')->default(false)->after('btn_border_color');
            $table->string('btn_text_active')->default('Jugar ahora')->after('btn_border');
            $table->string('btn_text_inactive')->default('Ver resultado')->after('btn_text_active');
            $table->boolean('btn_shadow')->default(false)->after('btn_text_inactive');
            $table->string('btn_text_color')->after('btn_shadow');
        });

        Schema::table('share_quizzes', function (Blueprint $table) {
            $table->string('game_banner_url',500)->nullable()->after('game_banner');
            $table->string('game_banner_video',500)->nullable()->after('game_banner_url');
            $table->string('btn_background_color_1')->nullable()->after('game_banner_video');
            $table->string('btn_background_color_2')->nullable()->after('btn_background_color_1');
            $table->string('btn_border_color')->nullable()->after('btn_background_color_2');
            $table->boolean('btn_border')->default(false)->after('btn_border_color');
            $table->string('btn_text_active')->default('Jugar ahora')->after('btn_border');
            $table->string('btn_text_inactive')->default('Ver resultado')->after('btn_text_active');
            $table->boolean('btn_shadow')->default(false)->after('btn_text_inactive');
            $table->string('btn_text_color')->after('btn_shadow');
        });

        Schema::table('vote_contests', function (Blueprint $table) {
            $table->string('game_banner_url',500)->nullable()->after('game_banner');
            $table->string('game_banner_video',500)->nullable()->after('game_banner_url');
            $table->string('btn_background_color_1')->nullable()->after('game_banner_video');
            $table->string('btn_background_color_2')->nullable()->after('btn_background_color_1');
            $table->string('btn_border_color')->nullable()->after('btn_background_color_2');
            $table->boolean('btn_border')->default(false)->after('btn_border_color');
            $table->string('btn_text_active')->default('Jugar ahora')->after('btn_border');
            $table->string('btn_text_inactive')->default('Ver resultado')->after('btn_text_active');
            $table->boolean('btn_shadow')->default(false)->after('btn_text_inactive');
            $table->string('btn_text_color')->after('btn_shadow');
        });

        Schema::table('click_wins', function (Blueprint $table) {
            $table->string('game_banner_url',500)->nullable()->after('featured_image_disabled');
            $table->string('game_banner_video',500)->nullable()->after('game_banner_url');
            $table->string('btn_background_color_1')->nullable()->after('game_banner_video');
            $table->string('btn_background_color_2')->nullable()->after('btn_background_color_1');
            $table->string('btn_border_color')->nullable()->after('btn_background_color_2');
            $table->boolean('btn_border')->default(false)->after('btn_border_color');
            $table->string('btn_text_active')->default('Jugar ahora')->after('btn_border');
            $table->string('btn_text_inactive')->default('Ver resultado')->after('btn_text_active');
            $table->boolean('btn_shadow')->default(false)->after('btn_text_inactive');
            $table->string('btn_text_color')->after('btn_shadow');
        });

        Schema::table('aplazo_games', function (Blueprint $table) {
            $table->string('game_banner_url',500)->nullable()->after('game_banner');
            $table->string('game_banner_video',500)->nullable()->after('game_banner_url');
            $table->string('btn_background_color_1')->nullable()->after('game_banner_video');
            $table->string('btn_background_color_2')->nullable()->after('btn_background_color_1');
            $table->string('btn_border_color')->nullable()->after('btn_background_color_2');
            $table->boolean('btn_border')->default(false)->after('btn_border_color');
            $table->string('btn_text_active')->default('Jugar ahora')->after('btn_border');
            $table->string('btn_text_inactive')->default('Ver resultado')->after('btn_text_active');
            $table->boolean('btn_shadow')->default(false)->after('btn_text_inactive');
            $table->string('btn_text_color')->after('btn_shadow');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_types', function (Blueprint $table) {
            $table->dropColumn([
                'game_banner_url',
                'game_banner_video'
            ]);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'game_banner_url',
                'game_banner_video',
                'btn_background_color_1',
                'btn_background_color_2',
                'btn_border_color',
                'btn_border',
                'btn_text_active',
                'btn_text_inactive',
                'btn_shadow',
                'btn_text_color'
            ]);
        });

        Schema::table('memory_quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'game_banner_url',
                'game_banner_video',
                'btn_background_color_1',
                'btn_background_color_2',
                'btn_border_color',
                'btn_border',
                'btn_text_active',
                'btn_text_inactive',
                'btn_shadow',
                'btn_text_color'
            ]);
        });

        Schema::table('share_quizzes', function (Blueprint $table) {
            $table->dropColumn([
                'game_banner_url',
                'game_banner_video',
                'btn_background_color_1',
                'btn_background_color_2',
                'btn_border_color',
                'btn_border',
                'btn_text_active',
                'btn_text_inactive',
                'btn_shadow',
                'btn_text_color'
            ]);
        });

        Schema::table('vote_contests', function (Blueprint $table) {
            $table->dropColumn([
                'game_banner_url',
                'game_banner_video',
                'btn_background_color_1',
                'btn_background_color_2',
                'btn_border_color',
                'btn_border',
                'btn_text_active',
                'btn_text_inactive',
                'btn_shadow',
                'btn_text_color'
            ]);
        });

        Schema::table('click_wins', function (Blueprint $table) {
            $table->dropColumn([
                'game_banner_url',
                'game_banner_video',
                'btn_background_color_1',
                'btn_background_color_2',
                'btn_border_color',
                'btn_border',
                'btn_text_active',
                'btn_text_inactive',
                'btn_shadow',
                'btn_text_color'
            ]);
        });

        Schema::table('aplazo_games', function (Blueprint $table) {
            $table->dropColumn([
                'game_banner_url',
                'game_banner_video',
                'btn_background_color_1',
                'btn_background_color_2',
                'btn_border_color',
                'btn_border',
                'btn_text_active',
                'btn_text_inactive',
                'btn_shadow',
                'btn_text_color'
            ]);
        });
    }
};
