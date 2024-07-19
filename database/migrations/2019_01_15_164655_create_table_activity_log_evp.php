<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableActivityLogEvp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log_evp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('evp_id');
            $table->integer('volunteer_id');
            $table->timestamp('waktu');
            $table->string('lokasi');
            $table->longText('aktivitas');
            $table->string('foto_1')->nullable();
            $table->string('foto_2')->nullable();
            $table->string('foto_3')->nullable();
            $table->timestamp('approve_atasan')->nullable();
            $table->timestamp('approve_vendor')->nullable();
            $table->string('status')->default('ACTV');
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
        Schema::drop('activity_log_evp');
    }
}
