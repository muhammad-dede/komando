<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttachmentTable extends Migration {

	public function up()
	{
		Schema::create('attachment', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('coc_id')->unsigned()->index();
			$table->string('judul', 100);
			$table->string('filename', 255);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('attachment');
	}
}