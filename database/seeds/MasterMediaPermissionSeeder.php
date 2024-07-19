<?php

use App\Module;
use App\Permission;
use Illuminate\Database\Seeder;

class MasterMediaPermissionSeeder extends Seeder
{
    protected $module;

    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $permissions = [
            new Permission(
                [
                    'name' => 'md_media',
                    'display_name' => 'Media',
                    'description' => 'Manajemen media/banner/background/video',
                    'status' => 'ACTV',
                ]
            ),
        ];

        $masdatModule = Module::where('name', 'master_data')->first();
        if ($masdatModule) {
            foreach ($permissions as $permission) {
                try {
                    $id = (new Permission())->getLastID();
                    $permission->id = $id;
                    $masdatModule->permissions()->save($permission);
                } catch (\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $exception) {
                    app('sentry')->captureException($exception);
                }
            }
        }
    }
}
