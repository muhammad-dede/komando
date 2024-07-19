<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoCompleteLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autocomplete_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coc_id')->unsigned();
            $table->date('tanggal_coc');
            $table->integer('realisasi_coc_id')->unsigned()->nullable();
            $table->string('type');
            $table->text('text');
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
        Schema::drop('autocomplete_log');
    }
}
