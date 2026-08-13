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
       Schema::create('api_request_logs', function (Blueprint $table) {
            $table->id();

            $table->string('method', 10);
            $table->string('endpoint');
            $table->string('tenant')->nullable();

            $table->string('client_id')->nullable();
            $table->string('ip_address', 45)->nullable();

            $table->unsignedSmallInteger('status_code')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();

            $table->json('request_data')->nullable();
            $table->longText('response_body')->nullable();

            $table->timestamp('requested_at');

            $table->timestamps();

            $table->index('endpoint');
            $table->index('tenant');
            $table->index('requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
