<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMediaKitTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('media_kit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('judul');
            $table->string('jenis');
            $table->string('status')->default(\App\Enum\MediaKitStatus::ACTIVE)->comment('see '.\App\Enum\MediaKitStatus::class);
            $table->integer('created_by');
            $table->integer('modified_by')
                ->nullable();
            $table->integer('deleted_by')
                ->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('media_kit');
    }
}
