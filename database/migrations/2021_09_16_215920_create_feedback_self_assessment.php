<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackSelfAssessment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_assessment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('nip',20);
            $table->string('nama');
            $table->longText('feedback');
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
        Schema::drop('feedback_assessment');
    }
}
