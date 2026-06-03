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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('app_name')->nullable();
            $table->string('app_description')->nullable();
            $table->string('app_logo')->nullable();
            $table->string('app_background_color')->nullable();
            $table->string('app_background')->nullable();
            $table->string('app_animated_background')->nullable();
            $table->string('primary_button_color')->nullable();
            $table->string('primary_button_background')->nullable();
            $table->text('home_content')->nullable();
            $table->text('terms_text')->nullable();
            $table->text('privacy_text')->nullable();
            $table->string('disabled_gradient_1');
            $table->string('disabled_gradient_2');
            $table->string('games_icon',500);
            $table->string('games_icon_active',500);
            $table->string('tickets_icon',500);
            $table->string('tickets_icon_active',500);
            $table->string('ranking_icon',500);
            $table->string('ranking_icon_active',500);
            $table->string('first_place_icon',500)->nullable();
            $table->string('second_place_icon',500)->nullable();
            $table->string('third_place_icon',500)->nullable();
            $table->boolean('members_number')->default(true);
            $table->string('members_legend');
            $table->string('members_placeholder');
            $table->string('members_url');
            $table->string('cards_background_color')->nullable();
            $table->string('cards_font_color');
            $table->string('out_of_coupons_title');
            $table->string('out_of_coupons_image',500);
            $table->boolean('tickets_quiz_validation')->default(false);
            $table->string('tickets_success_response',500);
            $table->string('tickets_failed_response',500);
            $table->integer('tickets_points')->default(1);
            $table->string('ranking_color_1')->nullable();
            $table->string('ranking_color_2')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
