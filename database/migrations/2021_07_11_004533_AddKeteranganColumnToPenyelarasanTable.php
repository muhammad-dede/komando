<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddKeteranganColumnToPenyelarasanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penyelarasan', function (Blueprint $table) {
            $table->text('keterangan_aksi_nyata')->nullable()->after('aksi_nyata');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penyelarasan', function (Blueprint $table) {
            $table->dropColumn('keterangan_aksi_nyata');
        });
    }
}
