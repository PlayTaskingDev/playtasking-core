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
         Schema::table('vote_contest_assets', function (Blueprint $table) {
            $table->string('title', 600)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vote_contest_assets', function (Blueprint $table) {
            $table->integer('title', 100)->change();
        });
    }
};
