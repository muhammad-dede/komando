<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutocompleteRealisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('realisasi_coc', function (Blueprint $table) {
            $table->integer('autocomplete')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('realisasi_coc', function (Blueprint $table) {
            $table->dropColumn('autocomplete');
        });
    }
}
