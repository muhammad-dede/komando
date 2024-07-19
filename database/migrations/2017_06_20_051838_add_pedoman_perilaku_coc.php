<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPedomanPerilakuCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coc', function (Blueprint $table) {
            $table->integer('pedoman_perilaku_id')->nullable()->unsigned()->index();
            $table->foreign('pedoman_perilaku_id')->references('id')->on('m_pedoman_perilaku')
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
        Schema::table('coc', function (Blueprint $table) {
            $table->dropColumn('pedoman_perilaku_id');
        });
    }
}
