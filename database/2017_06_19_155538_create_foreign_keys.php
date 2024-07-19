<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('coc', function(Blueprint $table) {
			$table->integer('materi_id')->nullable()->unsigned()->index();
			$table->foreign('materi_id')->references('id')->on('materi')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('coc', function(Blueprint $table) {
			$table->integer('leader_id')->nullable()->unsigned()->index();
			$table->foreign('leader_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('tema_coc', function(Blueprint $table) {
			$table->integer('materi_id')->nullable()->unsigned()->index();
			$table->foreign('materi_id')->references('id')->on('materi')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('materi', function(Blueprint $table) {
			$table->foreign('tema_id')->references('id')->on('m_tema')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('materi', function(Blueprint $table) {
			$table->foreign('penulis_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('attachment_materi', function(Blueprint $table) {
			$table->foreign('materi_id')->references('id')->on('materi')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('coc', function(Blueprint $table) {
			$table->dropForeign('coc_materi_id_foreign');
		});
		Schema::table('coc', function(Blueprint $table) {
			$table->dropForeign('coc_leader_id_foreign');
		});
		Schema::table('tema_coc', function(Blueprint $table) {
			$table->dropForeign('tema_coc_materi_id_foreign');
		});
		Schema::table('materi', function(Blueprint $table) {
			$table->dropForeign('materi_tema_id_foreign');
		});
		Schema::table('materi', function(Blueprint $table) {
			$table->dropForeign('materi_penulis_id_foreign');
		});
		Schema::table('attachment_materi', function(Blueprint $table) {
			$table->dropForeign('attachment_materi_materi_id_foreign');
		});
	}
}