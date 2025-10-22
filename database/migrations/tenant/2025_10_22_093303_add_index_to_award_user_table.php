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
        Schema::table('award_user', function (Blueprint $table) {
            // Índice compuesto optimizado
            $table->index(
                ['model_id', 'model_type', 'user_id', 'hit'],
                'idx_award_user_full'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down(): void
    {
        Schema::table('award_user', function (Blueprint $table) {
            $table->dropIndex('idx_award_user_full');
        });
    }
};
