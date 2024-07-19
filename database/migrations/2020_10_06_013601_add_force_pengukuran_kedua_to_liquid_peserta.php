<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForcePengukuranKeduaToLiquidPeserta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquid_peserta', function (Blueprint $table) {
            $table->boolean('force_pengukuran_kedua')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liquid_peserta', function (Blueprint $table) {
            $table->dropColumn('force_pengukuran_kedua');
        });
    }
}
