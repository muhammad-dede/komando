<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_label', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keys');
            $table->string('translasi');
            $table->string('sort_translasi');
            $table->string('status');
			$table->integer('created_by')->nullable();
			$table->integer('modified_by')
				->nullable()
				->unsigned();
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
        Schema::drop('config_label');
    }
}
