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
        Schema::create('click_wins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title',100);
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug')->unique();
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            //$table->string('game_banner',500)->nullable();
            $table->dateTime('init_date');
            $table->dateTime('end_date');

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
        Schema::dropIfExists('click_wins');
    }
};
