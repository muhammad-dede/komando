<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableValidasiCv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validasi_cv', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nip',15);
            $table->integer('kelengkapan_id');
            $table->float('jumlah');
            $table->float('progress');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('m_kelengkapan_cv', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->integer('target');
            $table->string('status')->default('ACTV');
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
        Schema::drop('validasi_cv');
        Schema::drop('m_kelengkapan_cv');
    }
}
