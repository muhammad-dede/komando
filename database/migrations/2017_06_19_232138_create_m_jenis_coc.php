<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMJenisCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_jenis_coc', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jenis');
            $table->timestamps();
        });

        Schema::table('coc', function(Blueprint $table) {
            $table->integer('jenis_coc_id')->nullable()->unsigned()->index();
            $table->foreign('jenis_coc_id')->references('id')->on('m_jenis_coc')
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
        Schema::table('coc', function(Blueprint $table) {
            $table->dropColumn('jenis_coc_id');
        });
        Schema::drop('m_jenis_coc');
    }
}
