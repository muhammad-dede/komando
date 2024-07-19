<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTempateBladePlnTerbaik extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_pln_terbaik', function (Blueprint $table) {
            $table->string('template')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_pln_terbaik', function (Blueprint $table) {
            $table->dropColumn('template');
        });
    }
}
