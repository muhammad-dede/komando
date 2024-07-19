<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLiquidPesertaAddSnapshotDivisi extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('liquid_peserta', function (Blueprint $table) {
            $table->string('snapshot_nama_atasan')->nullable();
            $table->string('snapshot_nama_bawahan')->nullable();
            $table->string('snapshot_nip_atasan')->nullable();
            $table->string('snapshot_nip_bawahan')->nullable();
            $table->string('snapshot_jabatan2_atasan')->nullable();
            $table->string('snapshot_jabatan2_bawahan')->nullable();
            $table->string('snapshot_unit_code')->nullable();
            $table->string('snapshot_unit_name')->nullable();
            $table->string('snapshot_plans')->nullable();
            $table->string('snapshot_orgeh_1')->nullable();
            $table->string('snapshot_orgeh_2')->nullable();
            $table->string('snapshot_orgeh_3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('liquid_peserta', function (Blueprint $table) {
            $table->dropColumn([
                'snapshot_nama_atasan',
                'snapshot_nama_bawahan',
                'snapshot_nip_atasan',
                'snapshot_nip_bawahan',
                'snapshot_jabatan2_atasan',
                'snapshot_jabatan2_bawahan',
                'snapshot_unit_code',
                'snapshot_unit_name',
                'snapshot_plans',
                'snapshot_orgeh_1',
                'snapshot_orgeh_2',
                'snapshot_orgeh_3',
            ]);
        });
    }
}
