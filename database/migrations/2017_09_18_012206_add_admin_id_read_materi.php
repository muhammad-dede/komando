<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminIdReadMateri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('read_materi', function (Blueprint $table) {
            $table->integer('coc_id')->index()->nullable();
            $table->integer('admin_id')->index()->nullable();
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
            $table->dropColumn('coc_id');
            $table->dropColumn('admin_id');
        });
    }
}
