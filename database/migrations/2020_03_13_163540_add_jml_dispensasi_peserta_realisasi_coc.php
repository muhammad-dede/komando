<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJmlDispensasiPesertaRealisasiCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('realisasi_coc', function (Blueprint $table) {
            $table->integer('jml_peserta_dispensasi')->unsigned()->nullable();
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
            $table->dropColumn('jml_peserta_dispensasi');
        });
    }
}
