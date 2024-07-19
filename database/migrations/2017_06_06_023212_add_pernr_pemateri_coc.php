<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPernrPemateriCoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coc', function (Blueprint $table) {
            $table->string('pernr_pemateri',15)->nullable()->index();
        });
        Schema::table('coc', function(Blueprint $table) {
            $table->dropColumn('pemateri_id');
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
            $table->integer('pemateri_id')->unsigned()->index();
            $table->foreign('pemateri_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('coc', function (Blueprint $table) {
            $table->dropColumn('pernr_pemateri');
        });
    }
}
