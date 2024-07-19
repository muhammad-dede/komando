<?php

use App\Models\Liquid\KelebihanKekuranganDetail;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class EditDeskripsiColumnKelebihanKekuranganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		KelebihanKekuranganDetail::truncate();
        Schema::table('kelebihan_kekurangan_detail', function (Blueprint $table) {
			$table->text('deskripsi_kekurangan')->after('deskripsi');
			$table->renameColumn('deskripsi', 'deskripsi_kelebihan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelebihan_kekurangan_detail', function (Blueprint $table) {
            $table->dropColumn('deskripsi_kekurangan');
        });
    }
}
