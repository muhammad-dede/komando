<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMateriTable extends Migration {

	public function up()
	{
		Schema::create('materi', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('tema_id')->unsigned()->index();
			$table->integer('pernr_penulis')->unsigned()->index();
			$table->string('judul', 100);
			$table->text('deskripsi');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('materi');
	}
}