<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMJenisMateri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_jenis_materi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jenis');
            $table->timestamps();
        });

        Schema::table('materi', function(Blueprint $table) {
            $table->integer('jenis_materi_id')->unsigned()->index();
            $table->foreign('jenis_materi_id')->references('id')->on('m_jenis_materi')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materi', function(Blueprint $table) {
            $table->dropColumn('jenis_materi_id');
        });

        Schema::drop('m_jenis_materi');
    }
}
