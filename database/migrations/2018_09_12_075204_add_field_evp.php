<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldEvp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_evp', function (Blueprint $table) {
            $table->integer('jenis_evp_id')->nullable();
            $table->integer('kuota')->nullable();
            $table->date('tgl_awal_registrasi')->nullable();
            $table->date('tgl_akhir_registrasi')->nullable();
            $table->date('tgl_pengumuman')->nullable();
            $table->timestamp('tgl_jam_briefing')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_evp', function (Blueprint $table) {
            $table->dropColumn('jenis_evp_id');
            $table->dropColumn('kuota');
            $table->dropColumn('tgl_awal_registrasi');
            $table->dropColumn('tgl_akhir_registrasi');
            $table->dropColumn('tgl_pengumuman');
            $table->dropColumn('tgl_jam_briefing');
        });
    }
}
