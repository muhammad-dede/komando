<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNipRealisasiCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('realisasi_coc', function (Blueprint $table) {
            $table->string('nip_leader', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('realisasi_coc', function (Blueprint $table) {
            $table->dropColumn('nip_leader');
        });
    }
}
