<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClusterUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_level_unit', function (Blueprint $table) {
            $table->string('company_code', 5)->nullable();
            $table->integer('cluster')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_level_unit', function (Blueprint $table) {
            $table->dropColumn('company_code');
            $table->dropColumn('cluster');
        });
    }
}
