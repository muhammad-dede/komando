<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->increments('id');
            $table->string('to');
            $table->string('from')->nullable();
            $table->string('subject');
            $table->string('message');
            $table->string('icon',50)->nullable()->default('fa fa-envelope-o');
            $table->string('color',20)->nullable()->default('info');
            $table->string('url')->nullable()->default('/');
            $table->string('status')->default('UNREAD');

            $table->timestamps();

            $table->primary('id');
            $table->index(['id', 'to', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notification');
    }
}
