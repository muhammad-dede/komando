<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->integer('user_id')->nullable();
        });
        Schema::table('read_materi', function (Blueprint $table) {
            $table->integer('user_id')->nullable();
        });
        Schema::table('tema_coc', function (Blueprint $table) {
            $table->integer('user_id_create')->nullable();
            $table->integer('user_id_update')->nullable();
        });
//        Schema::table('upload_data', function (Blueprint $table) {
//            $table->integer('user_id')->nullable();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('read_materi', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('read_materi', function (Blueprint $table) {
            $table->dropColumn('user_id_create');
            $table->dropColumn('user_id_update');
        });
//        Schema::table('upload_data', function (Blueprint $table) {
//            $table->dropColumn('user_id');
//        });

    }
}
