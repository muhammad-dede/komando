<?php

use Illuminate\Database\Seeder;

class RoleKomisarisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(['id' => '12', 'name'=>'komisaris', 'display_name'=>'Komisaris PLN', 'description'=>'Komisaris PLN']);

        // set permission role direksi PLN
        DB::table('permission_role')->insert(['permission_id' => '1', 'role_id'=>'12']);
        DB::table('permission_role')->insert(['permission_id' => '2', 'role_id'=>'12']);
        DB::table('permission_role')->insert(['permission_id' => '3', 'role_id'=>'12']);
        DB::table('permission_role')->insert(['permission_id' => '4', 'role_id'=>'12']);
        DB::table('permission_role')->insert(['permission_id' => '5', 'role_id'=>'12']);
        DB::table('permission_role')->insert(['permission_id' => '6', 'role_id'=>'12']);
        DB::table('permission_role')->insert(['permission_id' => '7', 'role_id'=>'12']);
        DB::table('permission_role')->insert(['permission_id' => '8', 'role_id'=>'12']);
        DB::table('permission_role')->insert(['permission_id' => '10', 'role_id'=>'12']);
        DB::table('permission_role')->insert(['permission_id' => '18', 'role_id'=>'12']);
        DB::table('permission_role')->insert(['permission_id' => '19', 'role_id'=>'12']);
    }
}
