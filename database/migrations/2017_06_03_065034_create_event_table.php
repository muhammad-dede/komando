<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventTable extends Migration {

	public function up()
	{
		Schema::create('event', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title', 100);
			$table->boolean('all_day')->nullable();
			$table->timestamp('start');
			$table->timestamp('end')->nullable();
			$table->string('url')->nullable();
			$table->string('class_name')->nullable();
			$table->boolean('editable')->nullable();
			$table->boolean('start_editable')->nullable();
			$table->boolean('duration_editable')->nullable();
			$table->boolean('resource_editable')->nullable();
			$table->string('rendering')->nullable();
			$table->boolean('overlap')->nullable();
			$table->string('constraint')->nullable();
			$table->string('source')->nullable();
			$table->string('color')->nullable();
			$table->string('background_color')->nullable();
			$table->string('border_color')->nullable();
			$table->string('text_color')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('event');
	}
}