<?php

use Illuminate\Database\Seeder;

class PermissionApproveLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert(['name' => 'log_approve', 'display_name'=>'Approve Log', 'description'=>'Approve Log Harian Relawan', 'module_id'=>'6']);
    }
}
