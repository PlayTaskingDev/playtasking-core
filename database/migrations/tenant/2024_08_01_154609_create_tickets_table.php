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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('transaction_number');
            $table->date('transaction_date');
            $table->string('store');
            $table->float('transaction_amount',8,2);
            $table->string('img_url',500);
            $table->integer('points');

            $table->foreignUuid('campaign_id');
            $table->foreignUuid('user_id');
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
        Schema::dropIfExists('tickets');
    }
};
