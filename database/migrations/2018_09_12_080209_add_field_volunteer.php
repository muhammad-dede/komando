<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldVolunteer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('volunteer', function (Blueprint $table) {
            $table->boolean('acc_pusat')->nullable();
            $table->longText('testimoni')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('volunteer', function (Blueprint $table) {
            $table->dropColumn('acc_pusat');
            $table->dropColumn('testimoni');
        });
    }
}
