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
        Schema::create('memory_cards', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('featured_image',500)->nullable();
            $table->string('name');
            $table->foreignUuid('memory_quiz_id')->index();

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
        Schema::dropIfExists('memory_cards');
    }
};
