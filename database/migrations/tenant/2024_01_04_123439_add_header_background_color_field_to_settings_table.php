<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('header_background_color')->nullable();
            $table->string('ga4_id')->nullable();
        });

        DB::table('settings')
            ->where('id', 1)
            ->update(
                ['header_background_color' => '#08285b','ga4_id' => 'G-HEZ3330FME']
            );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['header_background_color','ga4_id']);
        });
    }
};
