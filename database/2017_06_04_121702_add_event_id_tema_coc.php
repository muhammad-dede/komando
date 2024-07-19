<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventIdTemaCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tema_coc', function (Blueprint $table) {
            $table->integer('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('event');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tema_coc', function (Blueprint $table) {
            $table->dropColumn('event_id');
        });
    }
}
