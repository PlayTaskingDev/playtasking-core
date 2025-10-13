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
        Schema::create('vote_contests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title',100);
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            //$table->string('failed_response');
            //$table->string('failed_image');
            $table->string('slug')->unique();
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->string('asset_type');
            $table->string('asset_kb_size');
            $table->integer('points_per_vote');
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
        Schema::dropIfExists('vote_contests');
    }
};
