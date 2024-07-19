<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTableMIsuNasional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_isu_nasional', function (Blueprint $table) {
            $table->integer('jenis_isu_nasional_id')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_default')->default(false);        

            // // set nullable sub_header with DB::statement
            // DB::statement('ALTER TABLE m_isu_nasional MODIFY sub_header VARCHAR(255) NULL');
            
            // // create new column temporary
            // $table->string('temp_description',4000)->nullable();
            // $table->string('temp_sanksi', 4000)->nullable();

            // // copy value from description to temp_description
            // DB::statement('UPDATE m_isu_nasional SET temp_description = "description"');

            // // copy value from sanksi to temp_sanksi
            // DB::statement('UPDATE m_isu_nasional SET temp_sanksi = sanksi');

            // // drop column description
            // $table->dropColumn('description');

            // // drop column sanksi
            // $table->dropColumn('sanksi');

        });

        // create table m_jenis_isu_nasional
        Schema::create('m_jenis_isu_nasional', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->timestamps();
        });

        // insert data to m_jenis_isu_nasional
        DB::table('m_jenis_isu_nasional')->insert(
            array(
                array('description' => 'Poster'),
                array('description' => 'Aturan'),
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_isu_nasional', function (Blueprint $table) {
            $table->dropColumn('jenis_isu_nasional_id');
            $table->dropColumn('image');
            $table->dropColumn('is_default');

        });

        Schema::dropIfExists('m_jenis_isu_nasional');
    }
}
