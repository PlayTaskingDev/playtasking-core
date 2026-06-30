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
        Schema::create('user_interactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('model_id')->index();
            $table->string('model_title');
            $table->foreignUuid('user_id')->index();
            $table->boolean('hit')->default(false);
            $table->dateTime('hit_created_at',6)->nullable();
            $table->dateTime('hit_updated_at',6)->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_interactions');
    }
};
