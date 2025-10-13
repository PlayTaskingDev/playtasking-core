<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->dropColumn(['games_icon','games_icon_active','tickets_icon','tickets_icon_active','coupons_icon','coupons_icon_active']);
            
            $table->string('coupons_field_placeholder')->nullable();
            $table->string('games_banner',500)->nullable();
            $table->boolean('cards_shadow',500)->default(false);
        });

        Schema::table('content_types', function (Blueprint $table) {
            $table->string('icon',500)->nullable();
            $table->string('icon_active',500)->nullable();
            $table->string('gradient_1')->nullable();
            $table->string('gradient_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['coupons_field_placeholder','games_banner','cards_shadow']);

            $table->string('games_icon',500);
            $table->string('games_icon_active',500);
            $table->string('tickets_icon',500);
            $table->string('tickets_icon_active',500);
            $table->string('coupons_icon',500);
            $table->string('coupons_icon_active',500);
        });

        Schema::table('content_types', function (Blueprint $table) {
            $table->dropColumn(
                [
                    'icon','icon_active','gradient_1','gradient_2'
                ]
            );
        });
    }
};
