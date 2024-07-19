<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdatedAtMStrukturOrganisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_struktur_organisasi', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable();
        });

	Schema::table('m_struktur_organisasi_bak', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable();
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
            $table->dropColumn('updated_at');
        });

	Schema::table('m_struktur_organisasi_bak', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
    }
}
