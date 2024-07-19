<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVolunteer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('evp_id');
            $table->integer('user_id');
            $table->string('nama',100);
            $table->string('nip',20);
            $table->string('company_code',4);
            $table->string('business_area',4);
            $table->string('jabatan',100);
            $table->string('pernr_atasan',20)->nullable();
            $table->string('pernr_gm',20)->nullable();
            $table->string('file_cv')->nullable();
            $table->string('file_surat_pernyataan')->nullable();
            $table->string('file_surat_ijin_gm')->nullable();
            $table->string('file_surat_ijin_keluarga')->nullable();
            $table->string('file_surat_sehat')->nullable();
            $table->boolean('acc_atasan')->nullable();
            $table->boolean('acc_gm')->nullable();
            $table->boolean('pelanggaran_disiplin')->nullable();
            $table->boolean('cv')->nullable();
            $table->boolean('surat_pernyataan')->nullable();
            $table->boolean('surat_ijin_gm')->nullable();
            $table->boolean('surat_ijin_keluarga')->nullable();
            $table->boolean('surat_sehat')->nullable();
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
        Schema::drop('volunteer');
    }
}
