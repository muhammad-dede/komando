<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrioritasPengembangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peserta_assessment', function (Blueprint $table) {
            $table->integer('prioritas_assessmen_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peserta_assessment', function (Blueprint $table) {
            $table->dropColumn('prioritas_assessmen_id');
        });
    }
}
