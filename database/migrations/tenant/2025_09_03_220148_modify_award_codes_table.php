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
        Schema::table('award_codes', function (Blueprint $table) {
            $table->string('product')->nullable()->after('image_url');
            $table->string('validity')->nullable()->after('product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('award_codes', function (Blueprint $table) {
            $table->dropColumn(['product', 'validity']);
        });
    }
};
