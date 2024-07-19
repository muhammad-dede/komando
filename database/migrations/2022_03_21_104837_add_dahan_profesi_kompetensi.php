<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDahanProfesiKompetensi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_kompetensi', function (Blueprint $table) {
            $table->integer('dahan_profesi_id')->nullable();
            $table->string('kode_dahan')->nullable();
            $table->string('dahan_profesi')->nullable();
            $table->integer('profesi_id')->nullable();
            $table->string('kode_profesi')->nullable();
            $table->string('profesi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_kompetensi', function (Blueprint $table) {
            $table->dropColumn('dahan_profesi_id');
            $table->dropColumn('kode_dahan');
            $table->dropColumn('dahan_profesi');
            $table->dropColumn('profesi_id');
            $table->dropColumn('kode_profesi');
            $table->dropColumn('profesi');
        });
    }
}
