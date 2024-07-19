<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoonshotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // insert new data to m_isu_nasional
        DB::table('m_isu_nasional')->insert([
            "id" => 5,
            "isu_nasional" => "New Chapter of Transformation 2.0",
            "header" => "New Chapter of Transformation 2.0",
            "sub_header" => "Moonshot",
            "description" => "New Chapter of Transformation 2.0",
            "sanksi" => null,
            "status" => "ACTV",
            "created_at" => null,
            "updated_at" => null,
            "jenis_isu_nasional_id" => 1,
            "image" => "assets/images/moonshot.png",
            "is_default" => 1
        ]);
    }
}
