<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLiquidPesertaAddSnapshotJabatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquid_peserta', function (Blueprint $table) {
            $table->string('snapshot_jabatan_atasan')->nullable();
            $table->string('snapshot_jabatan_bawahan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liquid_peserta', function (Blueprint $table) {
            $table->dropColumn(['snapshot_jabatan_bawahan', 'snapshot_jabatan_atasan']);
        });
    }
}
