<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('to');
            $table->string('to_name');
            $table->string('subject');
            $table->string('file_view');
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
        Schema::drop('mail_log');
    }
}
