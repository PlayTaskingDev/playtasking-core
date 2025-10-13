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
        Schema::create('aplazo_loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('url',250);
            $table->string('loan_id');
            $table->string('cart_id');
            $table->string('token');
            $table->string('status');
            $table->foreignUuid('aplazo_game_id')->index();
            $table->foreignUuid('user_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplazo_loans');
    }
};
