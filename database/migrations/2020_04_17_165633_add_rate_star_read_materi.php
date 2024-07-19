<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRateStarReadMateri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('read_materi', function (Blueprint $table) {
            $table->integer('rate_star')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('read_materi', function (Blueprint $table) {
            $table->dropColumn('rate_star');
        });
    }
}
