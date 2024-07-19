<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_coc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coc_id')->unsigned()->index();
            $table->foreign('coc_id')->references('id')->on('coc');
            $table->string('folder');
            $table->string('filename');
            $table->string('judul');
            $table->string('deskripsi');
            $table->string('status', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gallery_coc');
    }
}
