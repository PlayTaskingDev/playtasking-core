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
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('ranking_enabled_games')->default(true)->after('app_active');
            $table->boolean('ranking_enabled_tickets')->default(true)->after('app_active');
            $table->string('first_place_icon_games',250)->nullable();
            $table->string('second_place_icon_games',250)->nullable();
            $table->string('third_place_icon_games',250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('ranking_enabled_games');
            $table->dropColumn('ranking_enabled_tickets');
            $table->dropColumn('first_place_icon_games');
            $table->dropColumn('second_place_icon_games');
            $table->dropColumn('third_place_icon_games');
        });
    }
};
