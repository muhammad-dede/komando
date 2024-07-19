<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePelanggaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_pelanggaran', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jenis_pelanggaran_id');
            $table->longText('description');
            $table->timestamps();
        });

        Schema::create('m_jenis_pelanggaran', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('pelanggaran_coc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id');
            $table->integer('orgeh');
            $table->integer('coc_id');
            $table->integer('jenis_pelanggaran_id');
            $table->integer('pelanggaran_id');
            $table->string('status',5)->default('ACTV');
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
        Schema::drop('pelanggaran_coc');
        Schema::drop('m_jenis_pelanggaran');
        Schema::drop('m_pelanggaran');
    }
}
