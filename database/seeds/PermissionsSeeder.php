<?php

use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addData();
    }

    public function addData()
    {
        $idPermision = DB::table('permissions')->max('id');
        $id1 = $idPermision + 1;
        $id2 = $id1 + 1;
        DB::table('permissions')->insert(['id'=> $id1, 'name' => 'md_config', 'display_name'=>'Konfigurasi Label', 'description'=>'Config label kekurangan, kelebihan dan saran', 'module_id'=>'5', 'status'=> 'ACTV']);
        DB::table('permissions')->insert(['id'=> $id2, 'name' => 'md_survey_question', 'display_name'=>'Pertanyaan Survey', 'description'=>'master data survey question', 'module_id'=>'5', 'status'=> 'ACTV']);
    }
}
