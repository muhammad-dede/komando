<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddAksiNyataColumnToPenyelarasan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penyelarasan', function (Blueprint $table) {
			$table->text('aksi_nyata')
				->nullable();
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
			$table->dropColumn('aksi_nyata');
		});
    }
}
