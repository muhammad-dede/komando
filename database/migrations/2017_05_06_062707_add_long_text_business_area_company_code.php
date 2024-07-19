<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLongTextBusinessAreaCompanyCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_business_area', function (Blueprint $table) {
            $table->string('long_text')->nullable();
        });
        Schema::table('m_company_code', function (Blueprint $table) {
            $table->string('long_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_business_area', function (Blueprint $table) {
            $table->dropColumn('long_text');
        });
        Schema::table('m_company_code', function (Blueprint $table) {
            $table->dropColumn('long_text');
        });
    }
}
