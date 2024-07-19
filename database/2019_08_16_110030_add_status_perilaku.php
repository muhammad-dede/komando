<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusPerilaku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_perilaku', function (Blueprint $table) {
            $table->string('status', 10)->default('ACTV');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_perilaku', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
