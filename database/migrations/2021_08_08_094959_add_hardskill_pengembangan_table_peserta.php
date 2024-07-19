<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHardskillPengembanganTablePeserta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peserta_assessment', function (Blueprint $table) {
            $table->longText('hardskill')->nullable();
            $table->longText('usulan_pengembangan')->nullable();
            $table->string('status_assessment')->default('Belum dilakukan penilaian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peserta_assessment', function (Blueprint $table) {
            $table->dropColumn('hardskill');
            $table->dropColumn('usulan_pengembangan');
            $table->dropColumn('status_assessment');
        });
    }
}
