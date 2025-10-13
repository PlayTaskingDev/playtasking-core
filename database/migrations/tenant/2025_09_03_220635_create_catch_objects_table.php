<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catch_objects', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('object_image',500); // Image of the object to catch
            
            $table->foreignUuid('catch_game_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catch_objects');
    }
};
