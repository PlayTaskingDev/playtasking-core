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
        Schema::table('codes', function (Blueprint $table) {
            $table->string('featured_image',500)->nullable()->after('points');
            $table->string('gradient_1')->nullable()->after('featured_image');
            $table->string('gradient_2')->nullable()->after('gradient_1');
            $table->string('btn_background_color_1')->nullable()->after('gradient_2');
            $table->string('btn_background_color_2')->nullable()->after('btn_background_color_1');
            $table->string('btn_border_color')->nullable()->after('btn_background_color_2');
            $table->boolean('btn_border')->default(false)->after('btn_border_color');
            $table->string('btn_text_active')->default('Jugar ahora')->after('btn_border');
            $table->string('btn_text_inactive')->default('Ver resultado')->after('btn_text_active');
            $table->boolean('btn_shadow')->default(false)->after('btn_text_inactive');
            $table->string('btn_text_color')->nullable()->after('btn_shadow');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('codes', function (Blueprint $table) {
            $table->dropColumn('featured_image');
            $table->dropColumn('gradient_1');
            $table->dropColumn('gradient_2');
            $table->dropColumn('btn_background_color_1');
            $table->dropColumn('btn_background_color_2');
            $table->dropColumn('btn_border_color');
            $table->dropColumn('btn_border');
            $table->dropColumn('btn_text_active');
            $table->dropColumn('btn_text_inactive');
            $table->dropColumn('btn_shadow');
            $table->dropColumn('btn_text_color');
        });
    }
};
