<?php

use App\Enum\RolesEnum;
use Illuminate\Database\Seeder;

class RoleAdminHtdArea extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $idRole = DB::table('roles')->max('id');
        $id1 = $idRole + 1;

        DB::table('roles')
            ->insert([
                'id' => $id1,
                'name' => RolesEnum::ADMIN_HTD,
                'display_name' => 'Admin HTD Area',
                'description' => 'Admin HTD Area',
            ]);
    }
}
