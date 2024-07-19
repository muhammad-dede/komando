<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClusterUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cluster_unit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('business_area',5)->index();
            $table->integer('orgeh')->nullable()->index();
            $table->integer('cluster')->index();
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
        Schema::drop('cluster_unit');
    }
}
