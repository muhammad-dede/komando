<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePegawaiAktif extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawai_aktif', function (Blueprint $table) {
//            $table->increments('id');
            $table->string('pernr',25);
            $table->string('name');
            $table->string('pa',5);
            $table->string('personel_area');
            $table->string('psubarea',5);
            $table->string('personel_subarea');
            $table->string('plans',10);
            $table->string('position');
            $table->string('nip',20);
            $table->string('email')->nullable();
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pegawai_aktif');
    }
}
