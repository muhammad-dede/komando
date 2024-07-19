<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataKepegawaianTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('holding')->nullable();
            $table->string('orgeh',15)->nullable();
            $table->string('plans',20)->nullable();
            $table->string('personel_area',5)->nullable();
            $table->string('personel_subarea',5)->nullable();
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
            $table->dropColumn('holding');
            $table->dropColumn('orgeh');
            $table->dropColumn('plans');
            $table->dropColumn('personel_area');
            $table->dropColumn('personel_subarea');
        });
    }
}
