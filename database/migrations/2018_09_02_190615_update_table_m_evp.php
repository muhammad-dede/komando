<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableMEvp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_evp', function (Blueprint $table) {
//            $table->string('foto')->nullable()->default('default.png');
            $table->string('nama_vendor')->nullable();
            $table->string('email_vendor')->nullable();

            $table->boolean('reg_atasan')->nullable();
            $table->boolean('reg_gm')->nullable();

            $table->boolean('keg_atasan')->nullable();
            $table->boolean('keg_vendor')->nullable();

            $table->boolean('briefing')->nullable();

            $table->string('dokumen')->nullable();
            $table->string('data_diri')->nullable();
            $table->string('surat_pernyataan')->nullable();

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
//            $table->dropColumn('foto')->nullable()->default('default.png');
            $table->dropColumn('nama_vendor')->nullable();
            $table->dropColumn('email_vendor')->nullable();

            $table->dropColumn('reg_atasan')->nullable();
            $table->dropColumn('reg_gm')->nullable();

            $table->dropColumn('keg_atasan')->nullable();
            $table->dropColumn('keg_vendor')->nullable();

            $table->dropColumn('briefing')->nullable();

            $table->dropColumn('dokumen')->nullable();
            $table->dropColumn('data_diri')->nullable();
            $table->dropColumn('surat_pernyataan')->nullable();
        });
    }
}
