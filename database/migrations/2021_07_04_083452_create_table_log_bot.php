<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLogBot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('chat_id');
            $table->string('in_out');
            $table->string('kode_organisasi')->nullable();
            $table->string('jenis_notif');
            $table->longText('message')->nullable();
            $table->string('status',10)->nullable();
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
        Schema::drop('bot_log');
    }
}
