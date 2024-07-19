<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReadMateriPegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('read_materi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 30)->index();
            $table->string('pernr', 15)->index();
            $table->string('nip', 20)->index();
            $table->integer('materi_id')->index();
            $table->foreign('materi_id')->references('id')->on('materi');
            $table->dateTime('tanggal_jam');
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
        Schema::drop('read_materi');
    }
}
