<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRealiasasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_jenjang', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jenjang_jabatan');
            $table->timestamps();
        });

        Schema::create('realisasi_coc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coc_id')->index();
            $table->foreign('coc_id')->references('id')->on('coc');
            $table->integer('level')->index();
            $table->integer('jenjang_id')->index();
            $table->foreign('jenjang_id')->references('id')->on('m_jenjang');
            $table->string('pernr_leader',15)->index();
            $table->string('company_code', 5)->index();
            $table->string('business_area', 5)->index();
            $table->date('realisasi');
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
        Schema::drop('realisasi_coc');
        Schema::drop('m_jenjang');
    }
}
