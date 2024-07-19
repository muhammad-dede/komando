<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log_book', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('liquid_id');
			$table->text('resolusi');
			$table->date('start_date');
			$table->date('end_date');
			$table->string('nama_kegiatan');
			$table->string('tempat_kegiatan');
			$table->string('keterangan');
			$table->integer('created_by');
			$table->integer('modified_by')
				->nullable();
			$table->integer('deleted_by')
				->nullable();
			$table->timestamps();
			$table->softDeletes();

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
        Schema::drop('activity_log_book');
    }
}
