<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRitualCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ritual_coc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->reference('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->index();
            $table->integer('orgeh')->reference('objid')->on('m_struktur_organisasi')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->index();
            $table->integer('coc_id')->reference('id')->on('coc')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->index();
            $table->integer('pedoman_id')->reference('id')->on('m_pedoman_perilaku')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->index();
            $table->integer('perilaku_id')->reference('id')->on('m_perilaku')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->index();
            $table->timestamps();
        });

        Schema::table('coc', function (Blueprint $table) {
            $table->integer('visi')->default('1')->nullable();
            $table->integer('misi')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ritual_coc');

        Schema::table('coc', function (Blueprint $table) {
            $table->dropColumn('visi');
            $table->dropColumn('misi');
        });
    }
}
