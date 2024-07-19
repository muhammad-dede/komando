<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateKelebihanKekuranganDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelebihan_kekurangan_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id');
			$table->string('kelebihan')
				->nullable();
			$table->string('kekurangan')
				->nullable();
			$table->text('deskripsi');
			$table->integer('created_by');
			$table->integer('modified_by')
				->nullable()
				->unsigned();
			$table->integer('deleted_by')
				->nullable()
				->unsigned();
			$table->timestamps();
			$table->softDeletes();
		});
		
		Schema::table('kelebihan_kekurangan_detail', function (Blueprint $table) {
			$table->foreign('parent_id')
				->references('id')
				->on('kelebihan_kekurangan')
				->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('kelebihan_kekurangan_detail');
    }
}
