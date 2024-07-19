<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMPlnGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_pln_group', function (Blueprint $table) {
            
            $table->string('company_code',4)->nullable();
            $table->string('business_area',4)->nullable();
            $table->string('orgeh',15)->nullable();
            $table->string('personel_area',5)->nullable();
            $table->string('personel_subarea',5)->nullable();
            $table->string('nip_leader',20)->nullable();
            $table->string('plans_leader',20)->nullable();
        });

        Schema::table('pegawai_shap', function (Blueprint $table) {
            $table->string('orgeh',15)->nullable();
            $table->string('plans',20)->nullable();
            $table->string('personel_area_sap',5)->nullable();
            $table->string('personel_subarea_sap',5)->nullable();
        });

        Schema::table('coc', function (Blueprint $table) {
            $table->string('nip_pemateri',20)->nullable();
            $table->string('nip_leader',20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_pln_group', function (Blueprint $table) {
            $table->dropColumn('company_code');
            $table->dropColumn('business_area');
            $table->dropColumn('orgeh');
            $table->dropColumn('nip_leader');
            $table->dropColumn('plans_leader');
            $table->dropColumn('personel_area');
            $table->dropColumn('personel_subarea');
        });

        Schema::table('pegawai_shap', function (Blueprint $table) {
            $table->dropColumn('orgeh');
            $table->dropColumn('plans');
            $table->dropColumn('personel_area_sap');
            $table->dropColumn('personel_subarea_sap');
        });

        Schema::table('coc', function (Blueprint $table) {
            $table->dropColumn('nip_pemateri');
            $table->dropColumn('nip_leader');
        });
    }
}
