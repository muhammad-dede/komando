<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabelT500p extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t500p', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mandt', 3)->index();
            $table->string('persa', 4)->index();
            $table->string('molga', 2);
            $table->string('bukrs', 4);
            $table->string('name1', 30);
            $table->string('name2', 40);
            $table->string('stras', 30);
            $table->string('pfach', 10);
            $table->string('pstlz', 10);
            $table->string('ort01', 25);
            $table->string('land1', 3);
            $table->string('regio', 3);
            $table->string('counc', 3);
            $table->string('cityc', 4);
            $table->string('adrnr', 10);

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
        Schema::drop('t500p');
    }
}
