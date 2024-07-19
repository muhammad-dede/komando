<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPohonDahanAssessmentPegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessment_pegawai', function (Blueprint $table) {
            $table->integer('dahan_profesi_id')->nullable();
            $table->string('kode_dahan_profesi',25)->nullable();
            $table->integer('profesi_id')->nullable();
            $table->string('kode_profesi',25)->nullable();
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_pegawai', function (Blueprint $table) {
            $table->dropColumn('dahan_profesi_id');
            $table->dropColumn('kode_dahan_profesi');
            $table->dropColumn('profesi_id');
            $table->dropColumn('kode_profesi');
        });
    }
}
