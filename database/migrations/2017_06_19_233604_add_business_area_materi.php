<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessAreaMateri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->string('company_code',4)->nullable()->index();
            $table->foreign('company_code')->references('company_code')->on('m_company_code')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('business_area',4)->nullable()->index();
            $table->foreign('business_area')->references('business_area')->on('m_business_area')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('orgeh')->nullable()->unsigned()->index();
//            $table->foreign('orgeh')->references('objid')->on('m_struktur_organisasi')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->dropColumn('company_code',4);
            $table->dropColumn('business_area',4);
            $table->dropColumn('orgeh');
        });
    }
}
