<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableJawabanPegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jawaban_pegawai', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('orgeh')->index();
            $table->integer('plans')->index();
            $table->integer('pedoman_perilaku_id')->index();
            $table->foreign('pedoman_perilaku_id')->references('id')->on('m_pedoman_perilaku');
            $table->integer('pertanyaan_id')->index();
            $table->foreign('pertanyaan_id')->references('id')->on('pertanyaan');
            $table->integer('jawaban_id')->index();
            $table->foreign('jawaban_id')->references('id')->on('jawaban');
            $table->integer('benar');
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
        Schema::drop('jawaban_pegawai');
    }
}
