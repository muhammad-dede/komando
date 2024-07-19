<?php

use App\Enum\RolesEnum;
use App\Module;
use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class LiquidPermissionSeeder extends Seeder
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
                    'name' => 'liquid_create_liquid',
                    'display_name' => 'Create Liquid',
                    'description' => 'Create Liquid',
                    'status' => 'ACTV',
                ]
            ),
            new Permission(
                [
                    'name' => 'liquid_view_all_unit',
                    'display_name' => 'View All Liquid',
                    'description' => 'View liquids from all unit',
                    'status' => 'ACTV',
                ]
            ),
            new Permission(
                [
                    'name' => 'liquid_view_unit_induk',
                    'display_name' => 'View Liquid From Unit Induk',
                    'description' => 'View Liquid From Unit Induk',
                    'status' => 'ACTV',
                ]
            ),
            new Permission(
                [
                    'name' => 'liquid_view_unit_pelaksana',
                    'display_name' => 'View Liquid From Unit Pelaksana',
                    'description' => 'View Liquid From Unit Pelaksana',
                    'status' => 'ACTV',
                ]
            ),
            new Permission(
                [
                    'name' => 'liquid_access_dashboard',
                    'display_name' => 'Access Liquid Dashboard',
                    'description' => 'Access Liquid Dashboard',
                    'status' => 'ACTV',
                ]
			),
			new Permission(
                [
                    'name' => 'liquid_info_detil_pelaksannan',
                    'display_name' => 'Liquid Info Detail Pelaksanaan',
                    'description' => 'Show Liquid Info Detail Pelaksanaan',
                    'status' => 'ACTV',
                ]
			),
			new Permission(
                [
                    'name' => 'liquid_send_notification_bawahan',
                    'display_name' => 'Liquid Send Notification Bawahan',
                    'description' => 'Send Liquid Activity Notification To Peserta Bawahan',
                    'status' => 'ACTV',
                ]
            ),
            new Permission(
                [
                    'name' => 'liquid_edit_peserta_bawahan',
                    'display_name' => 'Edit bawahan peserta',
                    'description' => 'Edit bawahan peserta Liquid (untuk admin divisi pusat)',
                    'status' => 'ACTV',
                ]
            ),
        ];

        $this->createLiquidModule();
        foreach ($permissions as $permission) {
            try {
                $id = (new Permission())->getLastID();
                if (empty($this->isExist($permission))) {
					$permission->id = $id;
                	$this->module->permissions()->save($permission);
				}
            } catch (\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $exception) {
                app('sentry')->captureException($exception);
            }
        }
    }

    public function createLiquidModule()
    {
        $moduleLiquid = Module::where('name', 'liquid')->first();

        if (!$moduleLiquid) {
            $moduleLiquid = new Module();
            $moduleLiquid->id = Module::getLastID();
            $moduleLiquid->name = 'liquid';
            $moduleLiquid->display_name = 'Liquid';
            $moduleLiquid->description = 'Modul Liquid';
            $moduleLiquid->save();
        }

        $this->module = $moduleLiquid;
	}

	public function isExist($permission)
	{
		return Permission::where('name', $permission->name)
			->first();
	}
}
