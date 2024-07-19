<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableActivityLogBookChangeDescriptionDatatype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE activity_log_book ADD keterangan_temp CLOB');
        \Illuminate\Support\Facades\DB::statement('UPDATE activity_log_book SET keterangan_temp = keterangan');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE activity_log_book DROP COLUMN keterangan');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE activity_log_book RENAME COLUMN keterangan_temp TO keterangan');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
