<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableForTalentReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // m_dirkom
        Schema::create('m_dirkom', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tahun');
            $table->string('description');
            $table->integer('jumlah_level')->unsigned();
            $table->string('status', 10)->default('ACTV');
            $table->timestamps();
        });

        // m_organisasi
        Schema::create('m_organisasi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->string('status', 10)->default('ACTV');
            $table->timestamps();
        });

        // m_jenjang_jabatan
        Schema::create('m_jenjang_jabatan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('main_group',2);
            $table->string('group');
            $table->string('sub_group',2);
            $table->string('description');
            $table->string('status', 10)->default('ACTV');
            $table->timestamps();
        });

        // m_status_rekomendasi
        Schema::create('m_status_rekomendasi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->string('status', 10)->default('ACTV');
            $table->timestamps();
        });

        // m_kamus_level
        Schema::create('m_kamus_level', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dirkom_id')->unsigned();
            $table->integer('level')->unsigned();
            $table->string('tingkat_kecakapan');
            $table->text('pedoman_kriteria_kinerja');
            $table->string('taksonomi_umum')->nullable();
            $table->string('status', 10)->default('ACTV');
            $table->timestamps();
        });

        // m_stream_business
        Schema::create('m_stream_business', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode');
            $table->text('description');
            $table->integer('periode')->unsigned();
            $table->string('status', 10)->default('ACTV');
            $table->timestamps();
        });

        // m_pohon_bisnis
        Schema::create('m_pohon_bisnis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode');
            $table->string('description');
            $table->string('status', 10)->default('ACTV');
            $table->timestamps();
        });

        // m_pohon_profesi
        Schema::create('m_pohon_profesi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pohon_bisnis_id')->unsigned();
            $table->string('kode');
            $table->string('description');
            $table->string('status', 10)->default('ACTV');
            $table->timestamps();
        });

        // m_dahan_profesi
        Schema::create('m_dahan_profesi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pohon_bisnis_id')->unsigned();
            $table->integer('pohon_profesi_id')->unsigned();
            $table->string('kode');
            $table->string('description');
            $table->string('status', 10)->default('ACTV');
            $table->timestamps();
        });

        // m_profesi
        Schema::create('m_profesi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pohon_bisnis_id')->unsigned();
            $table->integer('pohon_profesi_id')->unsigned();
            $table->integer('dahan_profesi_id')->unsigned();
            $table->string('kode');
            $table->string('description');
            $table->integer('stream_business_pu_id')->unsigned()->nullable();
            $table->integer('stream_business_npu_id')->unsigned()->nullable();
            $table->string('status', 10)->default('ACTV');
            $table->timestamps();
        });

        // p_dahan_stream_pu
        Schema::create('p_dahan_stream_pu', function (Blueprint $table) {
            $table->integer('stream_business_id')->unsigned();
            $table->integer('dahan_profesi_id')->unsigned();
        });

        // p_dahan_stream_npu
        Schema::create('p_dahan_stream_npu', function (Blueprint $table) {
            $table->integer('stream_business_id')->unsigned();
            $table->integer('dahan_profesi_id')->unsigned();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('m_dirkom');
        Schema::drop('m_organisasi');
        Schema::drop('m_jenjang_jabatan');
        Schema::drop('m_status_rekomendasi');
        Schema::drop('m_kamus_level');
        Schema::drop('m_stream_business');
        Schema::drop('m_pohon_bisnis');
        Schema::drop('m_pohon_profesi');
        Schema::drop('m_dahan_profesi');
        Schema::drop('m_profesi');
        Schema::drop('p_dahan_stream_pu');
        Schema::drop('p_dahan_stream_npu');
    }
}
