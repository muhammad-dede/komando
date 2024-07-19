<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateLiquidPesertaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquid_peserta', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('liquid_id');
			$table->string('atasan_id');
			$table->string('bawahan_id');
		});

		Schema::table('liquid_peserta', function (Blueprint $table) {
			$table->foreign('liquid_id')
				->references('id')
				->on('liquids')
				->onDelete('cascade');

			// TODO: belum jelas atasan dan bawahan ini refer ke user atau struktur_jabatan
			// $table->foreign('atasan_id')
			// 	->references('id')
			// 	->on('users')
			// 	->onDelete('cascade');
            //
			// $table->foreign('bawahan_id')
			// 	->references('id')
			// 	->on('users')
			// 	->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('liquid_peserta');
    }
}
