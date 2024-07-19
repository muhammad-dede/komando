<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttachmentMateriTable extends Migration {

	public function up()
	{
		Schema::create('attachment_materi', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('materi_id')->unsigned()->index();
			$table->string('judul', 100);
			$table->string('filename', 255);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('attachment_materi');
	}
}