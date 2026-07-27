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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('favicon',500)->nullable()->after('ranking_color_2');
            $table->string('reg_form_name_label',256)->nullable()->after('ranking_color_2');
            $table->string('reg_form_email_label',256)->nullable()->after('ranking_color_2');
            $table->string('reg_form_email_conf_label',256)->nullable()->after('ranking_color_2');
            $table->string('tickets_form_legend',256)->nullable()->after('ranking_color_2');
            $table->string('tickets_duplicated_image',500)->nullable()->after('ranking_color_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'favicon','reg_form_name_label','reg_form_email_label','reg_form_email_conf_label','tickets_form_legend','tickets_duplicated_image'
            ]);
        });
    }
};
