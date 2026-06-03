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
        Schema::table('users', function (Blueprint $table) {
            $table->json('extra_info')->nullable()->after('members_number'); 
        });
         Schema::table('settings', function (Blueprint $table) {
            $table->boolean('allow_city')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('extra_info');
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('allow_city');
        });
    }
};
