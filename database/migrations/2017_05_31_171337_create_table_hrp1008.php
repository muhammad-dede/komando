<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHrp1008 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrp1008', function (Blueprint $table) {
            $table->increments('id');
            $table->string('MANDT',3)->index();
            $table->string('PLVAR',2)->index();
            $table->string('OTYPE',2)->index();
            $table->string('OBJID',8)->index();
            $table->string('SUBTY',4);
            $table->string('ISTAT',1);
            $table->string('BEGDA',8);
            $table->string('ENDDA',8);
            $table->string('VARYF',10);
            $table->string('SEQNR',3);
            $table->string('INFTY',4);
            $table->string('OTJID',10);
            $table->string('AEDTM',8);
            $table->string('UNAME',12);
            $table->string('REASN',2);
            $table->string('HISTO',1);
            $table->string('ITXNR',8);
            $table->string('BUKRS',4)->index();
            $table->string('GSBER',4)->index();
            $table->string('WERKS',4);
            $table->string('PERSA',4)->index();
            $table->string('BTRTL',4)->index();
            $table->string('KOKRS',4)->index();

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
        Schema::drop('hrp1008');
    }
}
