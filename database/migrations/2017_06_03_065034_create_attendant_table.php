<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttendantTable extends Migration {

	public function up()
	{
		Schema::create('attendant', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('coc_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->index();
			$table->datetime('check_in');
			$table->string('lokasi')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('attendant');
	}
}