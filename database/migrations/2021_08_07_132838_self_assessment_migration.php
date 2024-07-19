<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SelfAssessmentMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_kompetensi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode',25);
            $table->string('judul_kompetensi');
            $table->string('judul_en');
            $table->longText('deskripsi');
            $table->string('status',10)->default('ACTV');
            $table->timestamps();
        });

        Schema::create('m_jabatan_self_asmnt', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sebutan_jabatan');
            $table->string('organisasi');
            $table->string('jenjang_jabatan');
            $table->string('pemimpin_unit');
            $table->string('stream_business');
            $table->string('profesi');
            $table->string('status',10)->default('ACTV');
            $table->timestamps();

        });

        Schema::create('m_level_kompetensi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_kompetensi',25);
            $table->integer('kompetensi_id');
            $table->integer('level');
            $table->longText('perilaku');
            $table->longText('contoh');
            $table->string('status',10)->default('ACTV');
            $table->timestamps();

        });

        Schema::create('level_kompetensi_jabatan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jabatan_id');
            $table->string('kode_kompetensi',25);
            $table->integer('kompetensi_id');
            $table->integer('level');
            $table->string('status',10)->default('ACTV');
            $table->timestamps();

        });

        Schema::create('jadwal_assessment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('periode');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->string('keterangan');
            $table->string('status',10)->default('ACTV');
            $table->timestamps();

        });

        Schema::create('peserta_assessment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jadwal_id');
            $table->integer('jabatan_id')->nullable();
            $table->string('nip_pegawai',25);
            $table->string('nama_pegawai');
            $table->string('jabatan_pegawai');
            $table->string('company_code',4);
            $table->string('business_area',4);
            $table->string('kode_posisi',10);
            $table->string('posisi');
            $table->string('nip_verifikator',25)->nullable();
            $table->string('nama_verifikator')->nullable();
            $table->string('jabatan_verifikator')->nullable();
            $table->string('kode_posisi_verifikator',10)->nullable();
            $table->string('status',10)->default('ACTV');
            $table->timestamps();

        });

        Schema::create('assessment_pegawai', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('peserta_id');
            $table->string('nip',25);
            $table->integer('jabatan_id');
            $table->string('kode_kompetensi',25);
            $table->integer('kompetensi_id');
            $table->integer('level_pegawai');
            $table->integer('level_penyelarasan')->nullable();
            $table->integer('level_final');
            $table->integer('level_kkj');
            $table->integer('gap_level');
            $table->string('evidence')->nullable();
            $table->string('keterangan')->nullable();
            $table->date('tanggal_input');
            $table->date('tanggal_verifikasi')->nullable();
            $table->date('tanggal_approve')->nullable();
            $table->integer('penyelarasan_id')->nullable();
            $table->string('status',10)->default('ACTV');
            $table->timestamps();

        });

        Schema::create('penyelarasan_assessment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jadwal_id');
            $table->date('tanggal');
            $table->string('tempat');
            $table->string('keterangan')->nullable();
            $table->string('status',10)->default('ACTV');
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
        Schema::drop('m_kompetensi');
        Schema::drop('m_jabatan_self_asmnt');
        Schema::drop('m_level_kompetensi');
        Schema::drop('level_kompetensi_jabatan');
        Schema::drop('jadwal_assessment');
        Schema::drop('peserta_assessment');
        Schema::drop('assessment_pegawai');
        Schema::drop('penyelarasan_assessment');
    }
}
