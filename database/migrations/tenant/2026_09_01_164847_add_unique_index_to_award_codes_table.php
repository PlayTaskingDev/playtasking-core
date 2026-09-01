<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'award_codes',
            function (Blueprint $table) {

                $table
                    ->unique(
                        'code',
                        'award_codes_code_unique'
                    );
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'award_codes',
            function (Blueprint $table) {

                $table->dropUnique(
                    'award_codes_code_unique'
                );
            }
        );
    }
};