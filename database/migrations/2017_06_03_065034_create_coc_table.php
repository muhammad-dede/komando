<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCocTable extends Migration {

	public function up()
	{
		Schema::create('coc', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('event_id')->unsigned()->index();
			$table->integer('tema_id')->unsigned()->index();
			$table->integer('pemateri_id')->unsigned()->index();
			$table->integer('admin_id')->unsigned()->index();
			$table->string('kode')->unique()->nullable();
			$table->datetime('tanggal_jam');
			$table->string('judul', 100);
			$table->string('deskripsi', 255);
			$table->string('status', 10)->default('OPEN');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('coc');
	}
}