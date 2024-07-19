<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMStrukturJabatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_struktur_jabatan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nip',15)->nullable()->index();
            $table->string('email',100)->nullable()->index();
            $table->string('pernr',15)->index();
            $table->string('cname')->nullable();
            $table->integer('plans')->index();
            $table->integer('orgeh')->index();
            $table->integer('werks')->index();
            $table->integer('btrtl')->index();
            $table->integer('mgrp')->nullable()->index();
            $table->integer('sgrp')->nullable()->index();
            $table->integer('spebe')->nullable()->index();
            $table->string('kostl',10)->nullable()->index();
            $table->integer('pernr_at')->index();
            $table->integer('plans_at')->index();
            $table->string('status',50)->nullable()->index();

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
        Schema::drop('m_struktur_jabatan');
    }
}
