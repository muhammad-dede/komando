<?php

use App\PegawaiSHAP;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class RoleSHAPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // // delete role holding if exists
        // Role::where('name', 'holding')->delete();

        // // add new role Holding
        // Role::create([
        //     'id' => (new Role())->getLastID(),
        //     'name' => 'holding',
        //     'display_name' => 'PLN Holding',
        //     'description' => 'PLN Holding',
        //     'status' => 'ACTV'
        // ]);

        // delete role shap if exists
        Role::where('name', 'shap')->delete();

        // add new role Sub Holding / Anak Perusahaan
        $role = Role::create([
            'id' => (new Role())->getLastID(),
            'name' => 'shap',
            'display_name' => 'Sub Holding / Anak Perusahaan',
            'description' => 'Sub Holding / Anak Perusahaan',
            'status' => 'ACTV'
        ]);

    }
}
