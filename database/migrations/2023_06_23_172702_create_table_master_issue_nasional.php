<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMasterIssueNasional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_isu_nasional', function (Blueprint $table) {
            $table->increments('id');
            $table->string('isu_nasional', 255);
            $table->string('header', 255);
            $table->string('sub_header', 255);
            $table->text('description');
            $table->text('sanksi');
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
        Schema::drop('m_isu_nasional');
    }
}
