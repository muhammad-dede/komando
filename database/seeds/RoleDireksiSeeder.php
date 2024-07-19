<?php

use Illuminate\Database\Seeder;

class RoleDireksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create role direksi PLN
        DB::table('roles')->insert(['id' => '8', 'name'=>'direksi', 'display_name'=>'Direksi PLN', 'description'=>'Direksi PLN']);

        // set permission role direksi PLN
        DB::table('permission_role')->insert(['permission_id' => '1', 'role_id'=>'8']);
        DB::table('permission_role')->insert(['permission_id' => '2', 'role_id'=>'8']);
        DB::table('permission_role')->insert(['permission_id' => '3', 'role_id'=>'8']);
        DB::table('permission_role')->insert(['permission_id' => '4', 'role_id'=>'8']);
        DB::table('permission_role')->insert(['permission_id' => '5', 'role_id'=>'8']);
        DB::table('permission_role')->insert(['permission_id' => '6', 'role_id'=>'8']);
        DB::table('permission_role')->insert(['permission_id' => '7', 'role_id'=>'8']);
        DB::table('permission_role')->insert(['permission_id' => '8', 'role_id'=>'8']);
        DB::table('permission_role')->insert(['permission_id' => '10', 'role_id'=>'8']);
        DB::table('permission_role')->insert(['permission_id' => '18', 'role_id'=>'8']);
        DB::table('permission_role')->insert(['permission_id' => '19', 'role_id'=>'8']);

    }
}
