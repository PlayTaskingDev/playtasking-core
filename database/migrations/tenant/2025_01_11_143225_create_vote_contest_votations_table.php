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
        Schema::create('vote_contest_votations', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('email');
            $table->integer('points');
            
            $table->foreignUuid('vote_contest_asset_id');

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
        Schema::dropIfExists('vote_contest_votations');
    }
};
