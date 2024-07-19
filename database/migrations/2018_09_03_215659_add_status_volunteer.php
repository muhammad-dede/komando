<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusVolunteer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('volunteer', function (Blueprint $table) {
            $table->timestamp('registrasi')->nullable();
            $table->timestamp('approval_atasan')->nullable();
            $table->timestamp('approval_gm')->nullable();
            $table->timestamp('approval_pusat')->nullable();
            $table->timestamp('briefing')->nullable();
            $table->timestamp('aktif')->nullable();
            $table->timestamp('finish')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('volunteer', function (Blueprint $table) {
            $table->dropColumn('registrasi');
            $table->dropColumn('approval_atasan');
            $table->dropColumn('approval_gm');
            $table->dropColumn('approval_pusat');
            $table->dropColumn('briefing');
            $table->dropColumn('aktif');
            $table->dropColumn('finish');
        });
    }
}
