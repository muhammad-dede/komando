<?php

use Illuminate\Database\Seeder;

class PermissionsShowMenuLiquidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $idPermision = DB::table('permissions')->max('id');
        $id1 = $idPermision + 1;
        DB::table('permissions')->updateOrInsert(['id'=> $id1, 'name' => 'show_menu_liquid', 'display_name'=>'Lihat Menu Liquid', 'description'=>'Lihat Menu Liquid', 'module_id'=>'7', 'status'=> 'ACTV']);
    }
}
