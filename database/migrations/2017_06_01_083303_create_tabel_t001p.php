<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabelT001p extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t001p', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mandt', 3)->index();
            $table->string('werks', 4)->index();
            $table->string('btrtl', 4)->index();
            $table->string('btext', 20);
            $table->string('molga', 2);
            $table->string('moura', 2);
            $table->string('trfgb', 2);
            $table->string('trfar', 2);
            $table->string('moabw', 2);
            $table->string('moabt', 2);
            $table->string('motat', 2);
            $table->string('mover', 2);
            $table->string('urlkl', 2);
            $table->string('juper', 4);
            $table->string('mobde', 2);
            $table->string('mozko', 2);
            $table->string('moptb', 2);
            $table->string('wktyp', 1);
            $table->string('mosta', 2);
            $table->string('mobur', 2);
            $table->string('mofid', 2);
            $table->string('mosid', 2);
            $table->string('modsz', 2);

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
        Schema::drop('t001p');
    }
}
