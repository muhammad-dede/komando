<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_evp', function (Blueprint $table) {
            $table->boolean('reg_admin_lv1')->nullable();
            $table->boolean('reg_admin_pusat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_evp', function (Blueprint $table) {
            $table->dropColumn('reg_admin_lv1');
            $table->dropColumn('reg_admin_pusat');
        });
    }
}
