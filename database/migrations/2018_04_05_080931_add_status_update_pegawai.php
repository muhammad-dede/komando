<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusUpdatePegawai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pegawai_baru',1)->nullable();
            $table->string('pernah_login',1)->nullable();
            $table->string('pegawai_mutasi',1)->nullable();
            $table->string('update_username',1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pegawai_baru');
            $table->dropColumn('pernah_login');
            $table->dropColumn('pegawai_mutasi');
            $table->dropColumn('update_username');
        });
    }
}
