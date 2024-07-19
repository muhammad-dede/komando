<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrgehRealisasiCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('realisasi_coc', function (Blueprint $table) {
            $table->integer('orgeh')->nullable();
            $table->integer('plans')->nullable();
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
            $table->dropColumn('orgeh');
            $table->dropColumn('plans');
        });
    }
}
