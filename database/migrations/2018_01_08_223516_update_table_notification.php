<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification', function (Blueprint $table) {
            $table->integer('user_id_to')->nullable()->index();
            $table->integer('user_id_from')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification', function (Blueprint $table) {
            $table->dropColumn('user_id_to');
            $table->dropColumn('user_id_from');
        });
    }
}
