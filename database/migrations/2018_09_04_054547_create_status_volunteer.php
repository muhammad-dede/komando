<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusVolunteer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_volunteer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('volunteer_id');
            $table->string('message');
            $table->string('approver_id')->nullable();
            $table->string('status',10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('status_volunteer');
    }
}
