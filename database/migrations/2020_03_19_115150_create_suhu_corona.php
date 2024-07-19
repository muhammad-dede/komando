<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuhuCorona extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suhu_badan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->date('tanggal');
            $table->float('suhu');
            $table->text('keterangan')->nullable();
            $table->string('status','5')->default('ACTV');
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
        Schema::drop('suhu_badan');
    }
}
