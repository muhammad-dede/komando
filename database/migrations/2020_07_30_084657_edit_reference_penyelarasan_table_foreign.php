<?php

use App\Models\Liquid\Penyelarasan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class EditReferencePenyelarasanTableForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Penyelarasan::query()->truncate();
        Schema::table('penyelarasan', function (Blueprint $table) {
			$table->string('atasan_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Penyelarasan::query()->truncate();
        Schema::table('penyelarasan', function (Blueprint $table) {
			$table->dropColumn('atasan_id');
		});
    }
}
