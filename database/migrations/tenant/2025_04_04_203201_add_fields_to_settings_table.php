<?php

use App\Models\Setting;
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
            $table->json('ocr_ticket_phrases')->nullable();
            $table->boolean('ocr_ticket_active')->default(false);
            $table->string('ocr_date_string')->nullable();
            $table->integer('ocr_date_characters')->nullable();
            $table->string('ocr_time_string')->nullable();
            $table->integer('ocr_time_characters')->nullable();
            $table->string('ocr_transaction_string')->nullable();
            $table->integer('ocr_transaction_characters')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['ocr_ticket_phrases','ocr_ticket_active','ocr_date_string','ocr_date_characters','ocr_time_string','ocr_time_characters','ocr_transaction_string','ocr_transaction_characters']);
        });
    }
};
