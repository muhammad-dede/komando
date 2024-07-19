<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableSelfAssessment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_jabatan_self_asmnt', function (Blueprint $table) {
            $table->integer('organisasi_id')->unsigned()->nullable();
            $table->integer('jenjang_jabatan_id')->unsigned()->nullable();
            $table->integer('job_target_jabatan_id')->unsigned()->nullable();
            $table->integer('job_target_jabatan')->unsigned()->nullable();
            $table->integer('stream_business_id')->unsigned()->nullable();
            $table->integer('profesi_id')->unsigned()->nullable();
            $table->integer('dirkom_id')->unsigned()->nullable();
        });

        Schema::table('peserta_assessment', function (Blueprint $table) {
            $table->float('skor_profesi')->nullable();
            $table->float('skor_dahan')->nullable();
            $table->integer('job_target_jabatan_id')->unsigned()->nullable();
            $table->integer('job_target_jabatan')->unsigned()->nullable();
            $table->integer('rekomendasi_profesi_id')->unsigned()->nullable();
            $table->integer('rekomendasi_dahan_id')->unsigned()->nullable();
        });

        Schema::table('m_kompetensi', function (Blueprint $table) {
            $table->integer('dirkom_id')->unsigned()->nullable();
        });

        Schema::table('m_level_kompetensi', function (Blueprint $table) {
            $table->integer('dirkom_id')->unsigned()->nullable();
        });

        Schema::table('level_kompetensi_jabatan', function (Blueprint $table) {
            $table->integer('dirkom_id')->unsigned()->nullable();
        });

        Schema::table('jadwal_assessment', function (Blueprint $table) {
            $table->integer('dirkom_id')->unsigned()->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_jabatan_self_asmnt', function (Blueprint $table) {
            $table->dropColumn('organisasi_id');
            $table->dropColumn('jenjang_jabatan_id');
            $table->dropColumn('job_target_jabatan_id');
            $table->dropColumn('job_target_jabatan');
            $table->dropColumn('stream_business_id');
            $table->dropColumn('profesi_id');
            $table->dropColumn('dirkom_id');
        });

        Schema::table('peserta_assessment', function (Blueprint $table) {
            $table->dropColumn('skor_profesi');
            $table->dropColumn('skor_dahan');
            $table->dropColumn('job_target_jabatan_id');
            $table->dropColumn('job_target_jabatan');
            $table->dropColumn('rekomendasi_profesi_id');
            $table->dropColumn('rekomendasi_dahan_id');
        });

        Schema::table('m_kompetensi', function (Blueprint $table) {
            $table->dropColumn('dirkom_id');
        });

        Schema::table('m_level_kompetensi', function (Blueprint $table) {
            $table->dropColumn('dirkom_id');
        });

        Schema::table('level_kompetensi_jabatan', function (Blueprint $table) {
            $table->dropColumn('dirkom_id');
        });

        Schema::table('jadwal_assessment', function (Blueprint $table) {
            $table->dropColumn('dirkom_id');
        });
    }
}
