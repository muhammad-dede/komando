<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('coc', function(Blueprint $table) {
			$table->foreign('tema_id')->references('id')->on('m_tema')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('coc', function(Blueprint $table) {
			$table->foreign('pemateri_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
//		Schema::table('coc', function(Blueprint $table) {
//			$table->foreign('admin_id')->references('id')->on('users')
//						->onDelete('restrict')
//						->onUpdate('restrict');
//		});
		Schema::table('coc', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('event')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('attendant', function(Blueprint $table) {
			$table->foreign('coc_id')->references('id')->on('coc')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('attendant', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('comment_coc', function(Blueprint $table) {
			$table->foreign('coc_id')->references('id')->on('coc')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('comment_coc', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('attachment', function(Blueprint $table) {
			$table->foreign('coc_id')->references('id')->on('coc')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('tema_coc', function(Blueprint $table) {
			$table->foreign('tema_id')->references('id')->on('m_tema')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('tema_coc', function(Blueprint $table) {
			$table->foreign('event_id')->references('id')->on('event')
				->onDelete('cascade')
				->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('coc', function(Blueprint $table) {
			$table->dropForeign('coc_tema_id_foreign');
		});
		Schema::table('coc', function(Blueprint $table) {
			$table->dropForeign('coc_pemateri_id_foreign');
		});
//		Schema::table('coc', function(Blueprint $table) {
//			$table->dropForeign('coc_admin_id_foreign');
//		});
		Schema::table('coc', function(Blueprint $table) {
			$table->dropForeign('coc_event_id_foreign');
		});
		Schema::table('attendant', function(Blueprint $table) {
			$table->dropForeign('attendant_coc_id_foreign');
		});
		Schema::table('attendant', function(Blueprint $table) {
			$table->dropForeign('attendant_user_id_foreign');
		});
		Schema::table('comment_coc', function(Blueprint $table) {
			$table->dropForeign('comment_coc_id_foreign');
		});
		Schema::table('comment_coc', function(Blueprint $table) {
			$table->dropForeign('comment_user_id_foreign');
		});
		Schema::table('attachment', function(Blueprint $table) {
			$table->dropForeign('attachment_coc_id_foreign');
		});
		Schema::table('tema_coc', function(Blueprint $table) {
			$table->dropForeign('tema_coc_tema_id_foreign');
		});
		Schema::table('tema_coc', function(Blueprint $table) {
			$table->dropForeign('tema_coc_event_id_foreign');
		});
	}
}