<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMEvp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_evp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_kegiatan');
            $table->date('waktu_awal');
            $table->date('waktu_akhir');
            $table->string('tempat');
            $table->text('deskripsi');
            $table->text('kriteria_peserta');
            $table->string('file_dokumen');
            $table->string('foto');
            $table->string('status',10)->default('ACTV');
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
        Schema::drop('m_evp');
    }
}
