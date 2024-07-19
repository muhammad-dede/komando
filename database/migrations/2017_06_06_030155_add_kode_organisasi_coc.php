<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKodeOrganisasiCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coc', function (Blueprint $table) {
            $table->string('company_code',4)->nullable()->index();
            $table->string('business_area',4)->nullable()->index();
            $table->integer('orgeh')->nullable()->index();
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
            $table->dropColumn('company_code');
            $table->dropColumn('business_area');
            $table->dropColumn('orgeh');
        });
    }
}
