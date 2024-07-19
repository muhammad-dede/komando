<?php

use App\Enum\KelebihanKekuranganStatus;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateKelebihanKekuranganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelebihan_kekurangan', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->text('deskripsi');
			$table->string('status');
			$table->integer('created_by');
			$table->integer('modified_by')
				->nullable()
				->unsigned();
			$table->integer('deleted_by')
				->nullable()
				->unsigned();
			$table->softDeletes();
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('kelebihan_kekurangan');
    }
}
