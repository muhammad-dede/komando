<?php

use App\Module;
use App\Permission;
use Illuminate\Database\Seeder;

class KelebihanKekuranganPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //TODO: pastikan seeder ini jika dijalankan berulang-ulang aman (IDEMPOTENT)
		$masdatModule	= Module::where('name', 'master_data')->first();
        $permission		= new Permission(
			[
				'id'			=> (new Permission())->getLastID(),
				'name'			=> 'md_kelebihan_kekurangan',
				'display_name'	=> 'Kelebihan Kekurangan',
				'description'	=> 'Master Data Kelebihan Kekurangan',
				'status'		=> 'ACTV'
			]
		);

        try {
            $masdatModule->permissions()->save($permission);
        } catch (\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $exception) {

        }
    }
}
