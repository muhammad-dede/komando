<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMStrukturPosisi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_struktur_posisi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('objid')->index();
            $table->string('stext')->nullable();
            $table->integer('relat')->index();
            $table->integer('sobid')->index();
            $table->string('stxt2')->nullable();
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
        Schema::drop('m_struktur_posisi');
    }
}
