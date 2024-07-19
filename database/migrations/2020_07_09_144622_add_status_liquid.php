<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusLiquid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquids', function (Blueprint $table) {
            $table->string('status')
                ->default(\App\Enum\LiquidStatus::DRAFT)
                ->comment("see ".\App\Enum\LiquidStatus::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liquids', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
