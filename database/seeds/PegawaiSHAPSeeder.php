<?php

use App\PegawaiSHAP;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class PegawaiSHAPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create new user from data pegawai shap
        $list_pegawai = PegawaiSHAP::all();
        foreach ($list_pegawai as $pegawai) {

            // check if user exists
            $user = User::where('username', $pegawai->username)->first();

            // if user not exists, create new user
            if (!$user) {
                $user = new User();
            }

            $user->username = $pegawai->username;
            $user->name = $pegawai->nama;
            $user->email = $pegawai->email;
            $user->password = bcrypt('FreeP@lsetin3!');
            $user->active_directory = 1;
            $user->company_code = $pegawai->company_code;
            $user->business_area = $pegawai->business_area;
            // $user->company_code = '1200';
            // $user->business_area = '1201';
            $user->status = 'ACTV';
            $user->domain = 'pusat';
            $user->nip = $pegawai->nip;
            $user->username2 = 'pusat\\' . $pegawai->username;
            $user->save();

            // reset user role
            $user->roles()->detach();

            // attach role shap
            $role = Role::where('name', 'shap')->first();
            $user->roles()->attach($role->id);
            echo $user->nip." - ".$user->name." - ".$user->username." - ".$user->email."\n";
        }
    }
}
