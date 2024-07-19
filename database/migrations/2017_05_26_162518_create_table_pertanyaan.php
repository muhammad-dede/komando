<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePertanyaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pertanyaan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pedoman_perilaku_id')->index();
            $table->foreign('pedoman_perilaku_id')->references('id')->on('m_pedoman_perilaku');
            $table->string('pertanyaan', 1000);
            $table->integer('jenis');
            $table->string('status',10)->default('ACTV');
            $table->timestamps();
        });

        Schema::create('jawaban', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pertanyaan_id')->index();
            $table->foreign('pertanyaan_id')->references('id')->on('pertanyaan');
            $table->integer('index');
            $table->string('jawaban');
            $table->boolean('benar');
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
        Schema::drop('jawaban');
        Schema::drop('pertanyaan');
    }
}
