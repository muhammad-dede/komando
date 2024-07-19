<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeaderPernrCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coc', function (Blueprint $table) {
            $table->string('pernr_leader',20)->nullable()->index();
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
            $table->dropColumn('pernr_leader');
        });
    }
}
