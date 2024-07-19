<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePenyelarasanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penyelarasan', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('liquid_id');
			$table->text('resolusi');
			$table->text('catatan_kekurangan')
				->nullable();
			$table->date('date_start')
				->nullable();
			$table->date('date_end')
				->nullable();
			$table->string('tempat')
				->nullable();
			$table->text('keterangan')
				->nullable();
			$table->integer('created_by');
			$table->integer('modified_by')
				->nullable();
			$table->integer('deleted_by')
				->nullable();

			$table->softDeletes();
			$table->timestamps();

			$table->foreign('liquid_id')
				->references('id')
				->on('liquids')
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
        Schema::drop('penyelarasan');
    }
}
