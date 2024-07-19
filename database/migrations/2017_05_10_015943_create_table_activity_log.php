<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableActivityLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',50)->index();
            $table->foreign('username')->references('username')->on('users');
            $table->string('text');
            $table->string('type')->nullable('');
            $table->string('ip_address', 64);
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
        Schema::drop('activity_log');
    }
}
