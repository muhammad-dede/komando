<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMTemaTable extends Migration {

	public function up()
	{
		Schema::create('m_tema', function(Blueprint $table) {
			$table->increments('id');
			$table->string('tema', 255);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('m_tema');
	}
}