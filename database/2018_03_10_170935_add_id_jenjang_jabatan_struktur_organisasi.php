<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdJenjangJabatanStrukturOrganisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_struktur_organisasi', function (Blueprint $table) {
            $table->integer('jenjang_id')->nullable();
        });
        Schema::table('m_struktur_organisasi_tmp', function (Blueprint $table) {
            $table->integer('jenjang_id')->nullable();
        });
        Schema::table('m_struktur_organisasi_bak', function (Blueprint $table) {
            $table->integer('jenjang_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_struktur_organisasi', function (Blueprint $table) {
            $table->dropColumn('jenjang_id');
        });
        Schema::table('m_struktur_organisasi_tmp', function (Blueprint $table) {
            $table->dropColumn('jenjang_id');
        });
        Schema::table('m_struktur_organisasi_bak', function (Blueprint $table) {
            $table->dropColumn('jenjang_id');
        });
    }
}
