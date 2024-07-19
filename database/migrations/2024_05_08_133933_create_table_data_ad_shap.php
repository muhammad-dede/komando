<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDataAdShap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawai_shap_ad', function (Blueprint $table) {
            $table->increments('id');
            // create new column for name, username, email, bidang, ou_name, company, office, nip, jabatan, full_name, logon_name, phone
            $table->string('name', 100);
            $table->string('username', 50);
            $table->string('email', 50)->nullable();
            $table->string('bidang')->nullable();
            $table->string('ou_name')->nullable();
            $table->string('company')->nullable();
            $table->string('office')->nullable();
            $table->string('nip', 30)->nullable();
            $table->string('jabatan')->nullable();
            $table->string('full_name')->nullable();
            $table->string('logon_name', 100)->nullable();
            $table->string('phone', 100)->nullable();
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
        Schema::drop('pegawai_shap_ad');
    }
}
