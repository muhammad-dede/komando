<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusRitual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ritual_coc', function (Blueprint $table) {
            $table->string('status',10)->nullable()->default('ACTV');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ritual_coc', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
