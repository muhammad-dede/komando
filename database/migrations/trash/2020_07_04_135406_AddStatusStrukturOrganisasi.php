<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusStrukturOrganisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_struktur_organisasi', function (Blueprint $table) {
            $table->string('status',10)->default('ACTV')->nullable();
        });
        Schema::table('m_struktur_organisasi_bak', function (Blueprint $table) {
            $table->string('status',10)->default('ACTV')->nullable();
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
            $table->dropColumn('status');
        });
        Schema::table('m_struktur_organisasi_bak', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
