<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDoDont extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_do_dont', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('urutan');
            $table->string('judul');
            $table->longText('deskripsi');
            $table->string('status', 10)->default('ACTV');
            $table->timestamps();
        });

//        Schema::table('m_perilaku', function (Blueprint $table) {
//            $table->integer('do_dont_id')->unsigned()->nullable();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('m_do_dont');

//        Schema::table('m_perilaku', function (Blueprint $table) {
//            $table->dropColumn('do_dont_id');
//        });
    }
}
