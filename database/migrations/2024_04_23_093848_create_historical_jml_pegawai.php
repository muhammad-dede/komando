<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricalJmlPegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_jml_pegawai', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('minggu_ke');
            $table->string('orgeh', 10);
            $table->string('company_code', 10);
            $table->string('nama_unit', 100);
            $table->integer('jml_pegawai');
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
        Schema::drop('history_jml_pegawai');
    }
}
