<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryColumnToKelebihanKekuranganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelebihan_kekurangan_detail', function (Blueprint $table) {
            $table->string('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelebihan_kekurangan_detail', function (Blueprint $table) {
            $table->dropColumn(['category']);
        });
    }
}
