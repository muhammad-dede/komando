<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTahunPerilakuJawabanPegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perilaku_pegawai', function (Blueprint $table) {
            $table->integer('tahun')->nullable();
        });
        Schema::table('jawaban_pegawai', function (Blueprint $table) {
            $table->integer('tahun')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perilaku_pegawai', function (Blueprint $table) {
            $table->dropColumn('tahun');
        });
        Schema::table('jawaban_pegawai', function (Blueprint $table) {
            $table->dropColumn('tahun');
        });
    }
}
