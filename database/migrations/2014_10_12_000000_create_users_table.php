<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
//            $table->increments('id');
//            $table->string('name');
//            $table->string('email')->unique();
//            $table->string('password');
//            $table->rememberToken();
//            $table->timestamps();

            $table->increments('id');
            $table->string('username', 30)->unique();
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('active_directory');
            $table->string('ad_display_name', 200)->nullable();
            $table->string('ad_mail', 100)->nullable();
            $table->string('ad_company', 100)->nullable();
            $table->string('ad_department', 100)->nullable();
            $table->string('ad_title', 100)->nullable();
            $table->string('ad_employee_number', 100)->nullable();
            $table->string('company_code', 4)->nullable();
            $table->string('business_area', 4)->nullable();
            $table->string('status', 10)->default('ACTV');
            $table->rememberToken();
            $table->timestamps();

            $table->index(['id', 'username', 'email', 'company_code', 'business_area']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
