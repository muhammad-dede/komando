<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMPerilakuDodont extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_perilaku_dodont', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('do_dont_id');
            $table->integer('nomor_urut');
            $table->integer('jenis');
            $table->longText('perilaku');
            $table->string('status', 10)->default('ACTV');
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
        Schema::drop('m_perilaku_dodont');
    }
}
