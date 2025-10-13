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
        Schema::create('memory_quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title');
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug');
            $table->integer('seconds');
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->string('back_card_image',500);
            $table->string('failed_image',500);
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');

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
        Schema::dropIfExists('memory_quizzes');
    }
};
