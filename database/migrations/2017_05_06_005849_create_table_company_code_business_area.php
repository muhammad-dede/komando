<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCompanyCodeBusinessArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_company_code', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_code', 4)->unique();
            $table->string('description');
            $table->string('short_text',100)->nullable();

            $table->string('status',10)->default('ACTV');
            $table->timestamps();

            $table->primary('id');
            $table->index(['id', 'company_code', 'status']);
        });

        Schema::create('m_business_area', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_code',4);
            $table->foreign('company_code')->references('company_code')->on('m_company_code');
            $table->string('business_area',4)->unique();
            $table->string('description');
            $table->string('short_text',100)->nullable();
            $table->string('city',100)->nullable();
            $table->string('address',200)->nullable();

            $table->string('status',10)->default('ACTV');
            $table->timestamps();

            $table->primary('id');
            $table->index(['id', 'company_code', 'business_area', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('m_company_code');
        Schema::drop('m_business_area');
    }
}
