<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBulanPesertaDanJadwal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jadwal_assessment', function (Blueprint $table) {
            $table->integer('bulan_periode')->nullable();
        });

        Schema::table('peserta_assessment', function (Blueprint $table) {
            $table->integer('bulan_periode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwal_assessment', function (Blueprint $table) {
            $table->dropColumn('bulan_periode');
        });

        Schema::table('peserta_assessment', function (Blueprint $table) {
            $table->dropColumn('bulan_periode');
        });
    }
}
