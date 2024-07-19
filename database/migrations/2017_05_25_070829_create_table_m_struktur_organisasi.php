<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMStrukturOrganisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_struktur_organisasi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('objid')->index();
            $table->string('stext');
            $table->integer('relat');
            $table->integer('sobid')->index();
            $table->string('stxt2');
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
        Schema::drop('m_struktur_organisasi');
    }
}
