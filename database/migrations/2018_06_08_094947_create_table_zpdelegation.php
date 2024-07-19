<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableZpdelegation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zpdelegation', function (Blueprint $table) {
            $table->string('position_1', 10)->index();
            $table->string('begda', 10)->nullable();
            $table->string('endda', 10)->nullable();
            $table->string('position_2', 10)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('zpdelegation');
    }
}
