<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSippTableCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coc', function (Blueprint $table) {
            $table->integer('saling_percaya')->nullable();
            $table->integer('integritas')->nullable();
            $table->integer('peduli')->nullable();
            $table->integer('pembelajar')->nullable();
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
            $table->dropColumn('saling_percaya');
            $table->dropColumn('integritas');
            $table->dropColumn('peduli');
            $table->dropColumn('pembelajar');
        });
    }
}
