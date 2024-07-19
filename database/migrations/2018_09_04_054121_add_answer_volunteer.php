<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnswerVolunteer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('volunteer', function (Blueprint $table) {
            $table->longText('answer_tertarik')->nullable();
            $table->longText('answer_tepat')->nullable();
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
            $table->dropColumn('answer_tertarik');
            $table->dropColumn('answer_tepat');
        });
    }
}
