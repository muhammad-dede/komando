<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMUnitMonitoring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_unit_monitoring', function (Blueprint $table) {
            $table->increments('id');
            $table->string('orgeh', 15);
            $table->string('company_code', 4);
            // $table->string('business_area', 4);
            $table->string('nama_unit');
            $table->string('sobid', 15)->nullable();
            $table->string('stxt2')->nullable();
            
            $table->float('target_realisasi_coc');
            $table->string('status', 10)->default('ACTV');
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
        Schema::drop('m_unit_monitoring');
    }
}
