<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLevelUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_level_unit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('werks', 4)->index();
            $table->string('btrtl', 4)->index();
            $table->string('btext', 20);
            $table->integer('level')->nullable();
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
        Schema::drop('m_level_unit');
    }
}
