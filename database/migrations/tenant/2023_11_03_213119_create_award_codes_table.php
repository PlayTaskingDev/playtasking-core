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
        Schema::create('award_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('code');
            $table->string('image_url',500)->nullable();
            $table->boolean('active')->default(false);
            $table->foreignUuid('award_id');
            $table->foreignUuid('user_id')->nullable();

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
        Schema::dropIfExists('award_codes');
    }
};
