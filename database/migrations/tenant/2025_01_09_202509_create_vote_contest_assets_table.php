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
        Schema::create('vote_contest_assets', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('title',100);
            $table->string('asset_url',500);
            $table->integer('points')->default(0);

            $table->foreignUuid('user_id');
            $table->foreignUuid('vote_contest_id');

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
        Schema::dropIfExists('vote_contest_assets');
    }
};
