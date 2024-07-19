<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableKomitmenPegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komitmen_pegawai', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('orgeh')->index();
            $table->integer('plans')->index();
            $table->integer('setuju');
            $table->integer('tahun')->nullable();
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
        Schema::drop('komitmen_pegawai');
    }
}
