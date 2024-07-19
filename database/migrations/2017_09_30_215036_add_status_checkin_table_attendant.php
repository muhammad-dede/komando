<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusCheckinTableAttendant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendant', function (Blueprint $table) {
            $table->integer('status_checkin_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendant', function (Blueprint $table) {
            $table->dropColumn('status_checkin_id');
        });
    }
}
