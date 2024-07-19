<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePengukuranSatuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengukuran_pertama', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('liquid_peserta_id');
			$table->text('resolusi')
				->nullable();
			$table->text('alasan')
				->nullable();
			$table->string('status');
			$table->integer('created_by');
			$table->integer('modified_by')
				->nullable();
			$table->integer('deleted_by')
				->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('liquid_peserta_id')
				->references('id')
				->on('liquid_peserta')
				->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pengukuran_pertama');
    }
}
