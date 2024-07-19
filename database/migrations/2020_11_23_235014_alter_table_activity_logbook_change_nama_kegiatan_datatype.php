<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableActivityLogbookChangeNamaKegiatanDatatype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE activity_log_book ADD nama_kegiatan_temp CLOB');
        \Illuminate\Support\Facades\DB::statement('UPDATE activity_log_book SET nama_kegiatan_temp = nama_kegiatan');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE activity_log_book DROP COLUMN nama_kegiatan');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE activity_log_book RENAME COLUMN nama_kegiatan_temp TO nama_kegiatan');
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
