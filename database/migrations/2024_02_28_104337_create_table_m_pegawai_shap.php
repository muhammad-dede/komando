<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTableMPegawaiShap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawai_shap', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nip', 18);
            $table->string('username', 100)->nullable();
            $table->string('email', 100)->nullable();

            $table->string('nama');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_telpon', 50)->nullable();
            $table->string('agama', 20)->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('status_nikah', 20)->nullable();

            $table->string('nip_atasan', 18)->nullable();
            $table->string('nama_atasan')->nullable();
            $table->string('jabatan_atasan')->nullable();

            $table->string('kode_pln_group', 10);
            $table->string('pln_group', 100);
            $table->string('ee_group', 100)->nullable();
            $table->string('ee_sub_group', 100)->nullable();

            $table->string('job_key')->nullable();
            $table->string('jabatan');
            $table->string('jenis_jabatan', 100)->nullable();
            $table->string('jenjang_jabatan', 100)->nullable();
            $table->string('kode_profesi', 100)->nullable();
            $table->string('nama_profesi')->nullable();

            $table->string('jenis_unit', 100)->nullable();
            $table->string('kelas_unit', 100)->nullable();
            $table->string('kode_daerah', 100)->nullable();
            $table->string('kota_organisasi', 100)->nullable();

            $table->string('company_code', 100)->nullable();
            $table->string('business_area', 100)->nullable();
            $table->string('personel_area', 100)->nullable();
            $table->string('personel_sub_area', 100)->nullable();

            $table->string('stream_business', 100)->nullable();
            $table->string('kode_posisi', 50)->nullable();
            $table->string('grade', 25)->nullable();

            $table->string('level_organisasi_1', 100)->nullable();
            $table->string('level_organisasi_2', 100)->nullable();
            $table->string('level_organisasi_3', 100)->nullable();
            $table->string('level_organisasi_4', 100)->nullable();
            $table->string('level_organisasi_5', 100)->nullable();
            $table->string('level_organisasi_6', 100)->nullable();
            $table->string('level_organisasi_7', 100)->nullable();
            $table->string('level_organisasi_8', 100)->nullable();
            $table->string('level_organisasi_9', 100)->nullable();
            $table->string('level_organisasi_10', 100)->nullable();
            $table->string('level_organisasi_11', 100)->nullable();
            $table->string('level_organisasi_12', 100)->nullable();
            $table->string('level_organisasi_13', 100)->nullable();
            $table->string('level_organisasi_14', 100)->nullable();
            $table->string('level_organisasi_15', 100)->nullable();

            $table->timestamps();
        });

        Schema::create('m_pln_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode');
            $table->string('description');
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
        Schema::drop('pegawai_shap');
        Schema::drop('m_pln_group');
    }
}
