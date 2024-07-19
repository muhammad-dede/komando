<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipePedomanPerilaku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_pedoman_perilaku', function (Blueprint $table) {
            $table->integer('tipe')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_pedoman_perilaku', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
}
