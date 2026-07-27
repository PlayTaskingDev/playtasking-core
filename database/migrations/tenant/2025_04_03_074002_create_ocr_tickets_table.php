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
        Schema::create('ocr_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('ocr_string');
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('transaction_number')->nullable();
            $table->string('img_url',500);

            $table->foreignUuid('campaign_id');
            $table->foreignUuid('user_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocr_tickets');
    }
};
