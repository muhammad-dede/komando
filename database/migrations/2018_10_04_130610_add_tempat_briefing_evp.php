<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTempatBriefingEvp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_evp', function (Blueprint $table) {
            $table->string('tempat_briefing')->nullable();
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
            $table->dropColumn('tempat_briefing');
        });
    }
}
