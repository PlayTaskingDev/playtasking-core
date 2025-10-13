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
        Schema::create('share_quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('title');
            $table->string('description');
            $table->string('slug');
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->string('featured_video_url')->nullable();
            $table->string('featured_image_url',500)->nullable();
            $table->string('share_url',500);
            $table->string('share_text',255);
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->dateTime('init_date');
            $table->dateTime('end_date');

            $table->foreignUuid('campaign_id');
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
        Schema::dropIfExists('share_quizzes');
    }
};
