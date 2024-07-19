<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvpBusinessArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_area_evp', function (Blueprint $table) {
            $table->string('business_area',4);
            $table->integer('evp_id');
            $table->timestamps();
        });
        Schema::create('company_code_evp', function (Blueprint $table) {
            $table->string('company_code',4);
            $table->integer('evp_id');
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
        Schema::drop('business_area_evp');
        Schema::drop('company_code_evp');
    }
}
