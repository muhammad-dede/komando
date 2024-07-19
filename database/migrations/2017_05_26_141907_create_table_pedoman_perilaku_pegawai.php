<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePedomanPerilakuPegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perilaku_pegawai', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('pedoman_perilaku_id')->index();
            $table->foreign('pedoman_perilaku_id')->references('id')->on('m_pedoman_perilaku');
            $table->integer('do');
            $table->integer('dont');
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
        Schema::drop('perilaku_pegawai');
    }
}
