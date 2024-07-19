<?php

use Illuminate\Database\Seeder;

class PermissionAssessment extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // create modul Self Assessment
        DB::table('module')->insert(['id' => '8', 'name'=>'assessment', 'display_name'=>'Self Assessment', 'description'=>'Self Assessment']);

        // create role Admin Self Assessment
        DB::table('roles')->insert(['id' => '13', 'name'=>'admin_assessment', 'display_name'=>'Admin Self Assessment', 'description'=>'Admin Self Assessment']);

        // create permission
        DB::table('permissions')->insert(['name' => 'verifikator', 'display_name'=>'Verfikator', 'description'=>'Verifikator', 'module_id'=>'8']);
        DB::table('permissions')->insert(['name' => 'report_assessment', 'display_name'=>'Report Assessment', 'description'=>'Report Assessment', 'module_id'=>'8']);

    }
}
