<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_question', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question');
            $table->string('status');
			$table->integer('created_by')->nullable();
			$table->integer('modified_by')
				->nullable()
				->unsigned();
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
        Schema::drop('survey_question');
    }
}
