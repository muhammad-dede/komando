<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePivotIsuNasionalCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isu_nasional_coc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned();
            $table->integer('orgeh');
            $table->integer('coc_id')->unsigned();
            $table->integer('isu_nasional_id')->unsigned();
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
        Schema::drop('isu_nasional_coc');
    }
}
