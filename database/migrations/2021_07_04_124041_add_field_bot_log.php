<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldBotLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bot_log', function (Blueprint $table) {
            $table->string('update_id')->nullable();
            $table->string('message_id')->nullable();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('type')->nullable();
            $table->string('text')->nullable();
            $table->string('bot_command')->nullable();
            $table->boolean('is_bot')->nullable();
            $table->longText('json_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bot_log', function (Blueprint $table) {
            $table->dropColumn('update_id');
            $table->dropColumn('message_id');
            $table->dropColumn('username');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('type');
            $table->dropColumn('text');
            $table->dropColumn('bot_command');
            $table->dropColumn('is_bot');
            $table->dropColumn('json_update');
        });
    }
}
