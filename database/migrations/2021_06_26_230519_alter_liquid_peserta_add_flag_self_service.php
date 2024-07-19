<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLiquidPesertaAddFlagSelfService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquid_peserta', function (Blueprint $table) {
            $table->smallInteger('flag_self_service')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liquid_peserta', function (Blueprint $table) {
            $table->dropColumn(['flag_self_service']);
        });
    }
}
