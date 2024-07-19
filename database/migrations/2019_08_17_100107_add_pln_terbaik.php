<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlnTerbaik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coc', function (Blueprint $table) {
            $table->integer('sinergi')->nullable();
            $table->integer('profesional')->nullable();
            $table->integer('pelanggan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coc', function (Blueprint $table) {
            $table->dropColumn('sinergi');
            $table->dropColumn('profesional');
            $table->dropColumn('pelanggan');
        });
    }
}
