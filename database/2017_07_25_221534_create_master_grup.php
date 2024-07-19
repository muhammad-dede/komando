<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterGrup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_grup_coc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_code', 4)->index();
            $table->string('business_area', 4)->index();
            $table->integer('orgeh')->index();
            $table->string('nama_grup');
            $table->string('pernr_admin',15)->nullable();
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
        Schema::drop('m_grup_coc');
    }
}
