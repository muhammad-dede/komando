<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldJenisWaktuEvp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_evp', function (Blueprint $table) {
            $table->integer('jenis_waktu_id')->nullable();
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
            $table->dropColumn('jenis_waktu_id');
        });
    }
}
