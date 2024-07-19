<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPeriodePesertaAssessment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peserta_assessment', function (Blueprint $table) {
            $table->integer('periode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peserta_assessment', function (Blueprint $table) {
            $table->dropColumn('periode');
        });
    }
}
