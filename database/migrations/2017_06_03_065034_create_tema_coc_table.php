<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTemaCocTable extends Migration {

	public function up()
	{
		Schema::create('tema_coc', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('event_id')->unsigned()->index();
			$table->integer('tema_id')->unsigned()->index();
			$table->date('start_date');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('tema_coc');
	}
}