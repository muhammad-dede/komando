<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMPedomanPerilaku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_pedoman_perilaku', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('urutan');
            $table->string('nomor_urut');
            $table->string('pedoman_perilaku');
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
        Schema::drop('m_pedoman_perilaku');
    }
}
