<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServerActivityLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->string('server_name',20)->nullable();
            $table->string('server_ip',20)->nullable();
            $table->string('device',100)->nullable();
            $table->string('platform',100)->nullable();
            $table->string('browser',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn('server_ip');
            $table->dropColumn('server_name');
            $table->dropColumn('device');
            $table->dropColumn('platform');
            $table->dropColumn('browser');
        });
    }
}
