<?php

use Illuminate\Database\Migrations\Migration;

class FixEmptySnapshotPesertaLiquid extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\Artisan::call('liquid:fix-empty-peserta-snapshot');
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        //
    }
}
