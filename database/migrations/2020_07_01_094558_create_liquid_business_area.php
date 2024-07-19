<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiquidBusinessArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquid_business_area', function (Blueprint $table) {
            $table->unsignedInteger('liquid_id');
            $table->string('business_area', 4);
            $table->timestamps();

            $table->primary(['liquid_id', 'business_area']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('liquid_business_area');
    }
}
