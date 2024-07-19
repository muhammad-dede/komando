<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiquidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquids', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('kelebihan_kekurangan_id');
            $table->date('feedback_start_date');
            $table->date('feedback_end_date');
            $table->date('penyelarasan_start_date');
            $table->date('penyelarasan_end_date');
            $table->date('pengukuran_pertama_start_date');
            $table->date('pengukuran_pertama_end_date');
            $table->date('pengukuran_kedua_start_date');
            $table->date('pengukuran_kedua_end_date');
            $table->string('reminder_aksi_resolusi');
            $table->date('gathering_start_date')->nullable();
            $table->date('gathering_end_date')->nullable();
            $table->string('gathering_location')->nullable();
            $table->text('peserta')->nullable();

            // multiple files disimpan di tabel media (menggunakan package media-library)

            // audit trails
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable()->unsigned();
            $table->integer('deleted_by')->nullable()->unsigned();
            $table->softDeletes();
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
        Schema::drop('liquids');
    }
}
