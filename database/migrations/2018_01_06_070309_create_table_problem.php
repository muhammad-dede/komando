<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProblem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_code',4)->index();
            $table->string('business_area',4)->index()->nullable();
            $table->integer('user_id_pelapor')->index()->nullable();
            $table->dateTime('tgl_kejadian')->nullable();
            $table->string('nip',25)->nullable();
            $table->string('nama')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('unit')->nullable();
            $table->integer('server_id');
            $table->integer('grup_id');
            $table->longText('deskripsi');
            $table->longText('konfirmasi')->nullable();
            $table->integer('status')->default('1');
            $table->longText('cause')->nullable();
            $table->longText('resolution')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('m_server', function (Blueprint $table) {
            $table->increments('id');
            $table->string('server');
            $table->timestamps();
        });

        Schema::create('m_problem_grup', function (Blueprint $table) {
            $table->increments('id');
            $table->string('masalah');
            $table->timestamps();
        });

        Schema::create('m_problem_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
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
        Schema::drop('problem');
        Schema::drop('m_problem_grup');
        Schema::drop('m_problem_status');
    }
}
