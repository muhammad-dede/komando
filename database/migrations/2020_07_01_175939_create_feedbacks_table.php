<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('liquid_peserta_id');
			$table->string('atasan')
				->nullable();
			$table->text('kelebihan');
			$table->text('kekurangan');
			$table->text('harapan');
			$table->text('saran');
			$table->text('new_kelebihan')
				->nullable();
			$table->text('new_kekurangan')
				->nullable();
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
        Schema::drop('feedbacks');
    }
}
