<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrioritasPengembanganAssessment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessment_pegawai', function (Blueprint $table) {
            $table->boolean('prioritas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_pegawai', function (Blueprint $table) {
            $table->dropColumn('prioritas');
        });
    }
}
