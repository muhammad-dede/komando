<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKodePosisiLeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coc', function (Blueprint $table) {
            $table->integer('level_unit')->nullable();
            $table->integer('jenjang_id')->nullable();
            $table->string('plans_leader',20)->nullable();
            $table->string('delegation_leader',20)->nullable();
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
            $table->dropColumn('level_unit');
            $table->dropColumn('jenjang_id');
            $table->dropColumn('plans_leader');
            $table->dropColumn('delegation_leader');
        });
    }
}
