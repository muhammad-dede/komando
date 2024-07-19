<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLiveChatStreaming extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('streaming', function (Blueprint $table) {
            $table->boolean('live_chat_enabled')->default(false);
            $table->string('live_chat_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('streaming', function (Blueprint $table) {
            $table->dropColumn('live_chat_enabled');
            $table->dropColumn('live_chat_url');
        });
    }
}
