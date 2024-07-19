<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAndAddScopeCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('m_scope', function (Blueprint $table) {
//            $table->increments('id');
//            $table->string('scope',50);
//            $table->timestamps();
//        });

        Schema::table('coc', function (Blueprint $table) {
            $table->string('scope',10)->nullable()->index();
//            $table->foreign('scope_id')->references('id')->on('m_scope');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coc', function (Blueprint $table) {
            $table->dropColumn('scope');
        });
//        Schema::drop('m_scope');

    }
}
