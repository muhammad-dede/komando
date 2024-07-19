<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusRolePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('status')->nullable()->default('ACTV');
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('status')->nullable()->default('ACTV');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
