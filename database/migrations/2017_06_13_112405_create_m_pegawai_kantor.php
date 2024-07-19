<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMPegawaiKantor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_pegawai_kantor', function (Blueprint $table) {
            $table->string('pernr',15)->index();
            $table->integer('orgeh')->index();
            $table->integer('orgeh_1')->nullable()->index();
            $table->integer('orgeh_2')->nullable()->index();
            $table->integer('orgeh_3')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('m_pegawai_kantor');
    }
}
