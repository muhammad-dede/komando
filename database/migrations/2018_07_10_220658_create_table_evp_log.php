<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEvpLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evp_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('volunteer_id');
            $table->string('kegiatan');
            $table->date('tanggal');
            $table->time('jam_awal');
            $table->time('jam_akhir');
            $table->integer('durasi');
            $table->string('file_foto')->nullable();
            $table->text('testimoni')->nullable();
            $table->string('status',10)->default('ACTV');
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
        Schema::drop('evp_log');
    }
}
