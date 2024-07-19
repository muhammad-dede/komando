<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogInterface extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interface_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file');
            $table->longText('message');
            $table->string('status'); //sent or not
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
        Schema::drop('interface_log');
    }
}
