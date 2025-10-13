<?php

use App\Enums\CodeTypeEnum;
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
        Schema::create('codes', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->enum('type',CodeTypeEnum::values());
            $table->string('title');
            $table->integer('points');
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->boolean('active');
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
        Schema::dropIfExists('codes');
    }
};
