<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRundownEvp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rundown_evp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('evp_id');
            $table->string('kegiatan');
            $table->string('lokasi');
            $table->timestamp('tgl_jam_awal');
            $table->timestamp('tgl_jam_akhir');
            $table->string('penanggungjawab');
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
        Schema::drop('rundown_evp');
    }
}
