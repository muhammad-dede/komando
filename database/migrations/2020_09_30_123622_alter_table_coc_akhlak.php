<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCocAkhlak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coc', function (Blueprint $table) {
            $table->boolean('akhlak_amanah')->nullable();
            $table->boolean('akhlak_kompeten')->nullable();
            $table->boolean('akhlak_harmonis')->nullable();
            $table->boolean('akhlak_loyal')->nullable();
            $table->boolean('akhlak_adaptif')->nullable();
            $table->boolean('akhlak_kolaboratif')->nullable();
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
            $table->dropColumn('akhlak_amanah');
            $table->dropColumn('akhlak_kompeten');
            $table->dropColumn('akhlak_harmonis');
            $table->dropColumn('akhlak_loyal');
            $table->dropColumn('akhlak_adaptif');
            $table->dropColumn('akhlak_kolaboratif');
        });
    }
}
