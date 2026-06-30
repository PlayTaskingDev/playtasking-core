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
            $table->integer('seconds')->nullable()->after('description');
            $table->boolean('enable_chronometer')->default(false)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('seconds');
            $table->dropColumn('enable_chronometer');
        });
    }
};
