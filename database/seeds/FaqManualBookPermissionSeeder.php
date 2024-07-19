<?php

use App\Module;
use App\Permission;
use Illuminate\Database\Seeder;

class FaqManualBookPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $masdatModule	= Module::where('name', 'master_data')->first();
        $permission		= new Permission(
            [
                'id'			=> (new Permission())->getLastID(),
                'name'			=> 'md_faq_manual_book',
                'display_name'	=> 'faq manual book',
                'description'	=> 'Master Data Faq Manual Book',
                'status'		=> 'ACTV'
            ]
        );

        try {
            $masdatModule->permissions()->save($permission);
        } catch (\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $exception) {

        }
    }
}
