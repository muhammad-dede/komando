<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentTable extends Migration {

	public function up()
	{
		Schema::create('comment_coc', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('coc_id')->unsigned()->index();
			$table->integer('parent_id')->index();
			$table->string('comment', 255);
			$table->integer('user_id')->unsigned()->index();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('comment_coc');
	}
}