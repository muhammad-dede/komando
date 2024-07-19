<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTargetCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('target_coc', function (Blueprint $table) {
            Schema::drop('target_coc');
        });

        Schema::create('target_coc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tahun');
            $table->integer('jenjang_id')->index();
            $table->foreign('jenjang_id')->references('id')->on('m_jenjang');
            $table->integer('cluster');
            $table->integer('tw1');
            $table->integer('tw2');
            $table->integer('tw3');
            $table->integer('tw4');
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
        Schema::table('target_coc', function (Blueprint $table) {
            Schema::drop('target_coc');
        });

        Schema::create('target_coc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tahun');
            $table->integer('jenjang_id')->index();
            $table->foreign('jenjang_id')->references('id')->on('m_jenjang');
            $table->integer('t1_c1');
            $table->integer('t1_c2');
            $table->integer('t1_c3');
            $table->integer('t2_c1');
            $table->integer('t2_c2');
            $table->integer('t2_c3');
            $table->integer('t3_c1');
            $table->integer('t3_c2');
            $table->integer('t3_c3');
            $table->integer('t4_c1');
            $table->integer('t4_c2');
            $table->integer('t4_c3');
            $table->timestamps();
        });
    }
}
