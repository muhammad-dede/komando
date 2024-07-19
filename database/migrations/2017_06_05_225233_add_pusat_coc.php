<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPusatCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coc', function (Blueprint $table) {
            $table->integer('pusat')->nullable()->unsigned()->default('0')->index();
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
            $table->dropColumn('pusat');
        });
    }
}
