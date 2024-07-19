<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyQuestionDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_question_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_question_id');
            $table->integer('liquids_id');
            $table->integer('liquid_peserta_id');
            $table->integer('feedback_id')->nullable();
            $table->integer('answer');
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')
                ->nullable()
                ->unsigned();
            $table->timestamps();            
        });

        Schema::table('survey_question_detail', function (Blueprint $table) {
			$table->foreign('feedback_id')->references('id')->on('feedbacks')->onDelete('cascade')->onUpdate('cascade');
		});

        Schema::table('survey_question_detail', function (Blueprint $table) {
			$table->foreign('liquids_id')->references('id')->on('liquids')->onDelete('cascade');
		});

        Schema::table('survey_question_detail', function (Blueprint $table) {
			$table->foreign('survey_question_id')->references('id')->on('survey_question')->onDelete('cascade');
		});

        Schema::table('survey_question_detail', function (Blueprint $table) {
			$table->foreign('liquid_peserta_id')->references('id')->on('liquid_peserta')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('config_label');
    }
}
