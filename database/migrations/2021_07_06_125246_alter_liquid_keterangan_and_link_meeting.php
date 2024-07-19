<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLiquidKeteranganAndLinkMeeting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquids', function (Blueprint $table) {
            $table->text('keterangan')->nullable();
            $table->text('link_meeting')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liquids', function (Blueprint $table) {
            $table->dropColumn(['keterangan', 'link_meeting']);
        });
    }
}
