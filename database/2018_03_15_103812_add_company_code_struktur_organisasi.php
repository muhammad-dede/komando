<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyCodeStrukturOrganisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_struktur_organisasi', function (Blueprint $table) {
            $table->string('company_code',4)->nullable();
        });
        Schema::table('m_struktur_organisasi_bak', function (Blueprint $table) {
            $table->string('company_code',4)->nullable();
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
            $table->dropColumn('company_code');
        });
        Schema::table('m_struktur_organisasi_bak', function (Blueprint $table) {
            $table->dropColumn('company_code');
        });
    }
}
