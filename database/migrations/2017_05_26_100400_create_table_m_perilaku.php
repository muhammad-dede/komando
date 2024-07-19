<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMPerilaku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_perilaku', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pedoman_perilaku_id')->index();
            $table->foreign('pedoman_perilaku_id')->references('id')->on('m_pedoman_perilaku');
            $table->integer('nomor_urut');
            $table->string('perilaku');
            $table->string('jenis',10);
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
        Schema::drop('m_perilaku');
    }
}
