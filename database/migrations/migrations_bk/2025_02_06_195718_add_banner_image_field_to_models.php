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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('game_banner',500)->after('content_type_id')->nullable();
        });
        Schema::table('share_quizzes', function (Blueprint $table) {
            $table->string('game_banner',500)->after('content_type_id')->nullable();
        });
        Schema::table('memory_quizzes', function (Blueprint $table) {
            $table->string('game_banner',500)->after('content_type_id')->nullable();
        });
        Schema::table('vote_contests', function (Blueprint $table) {
            $table->string('game_banner',500)->after('content_type_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('game_banner');
        });
        Schema::table('share_quizzes', function (Blueprint $table) {
            $table->dropColumn('game_banner');
        });
        Schema::table('memory_quizzes', function (Blueprint $table) {
            $table->dropColumn('game_banner');
        });
        Schema::table('vote_contests', function (Blueprint $table) {
            $table->dropColumn('game_banner');
        });
    }
};
