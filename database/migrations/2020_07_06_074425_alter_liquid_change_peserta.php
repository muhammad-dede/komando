<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLiquidChangePeserta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquids', function (Blueprint $table) {
            $table->renameColumn('peserta', 'peserta_snapshot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liquids', function (Blueprint $table) {
            $table->renameColumn('peserta_snapshot', 'peserta');
        });
    }
}
