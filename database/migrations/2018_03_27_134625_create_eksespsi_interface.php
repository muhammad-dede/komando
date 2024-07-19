<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEksespsiInterface extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interface_exclude', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nip',20);
            $table->string('pernr',20);
            $table->date('begda');
            $table->date('endda');
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
        Schema::drop('interface_exclude');
    }
}
