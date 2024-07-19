<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePlnTerbaik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_pln_terbaik', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('urutan')->unsigned();
            $table->string('nomor_urut',10);
            $table->integer('tipe')->nullable();
            $table->string('pedoman_perilaku');
//            $table->longText('deskripsi');
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
        Schema::drop('m_pln_terbaik');
    }
}
