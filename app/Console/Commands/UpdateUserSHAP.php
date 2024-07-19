<?php

namespace App\Console\Commands;

use App\PegawaiSHAP;
use App\Role;
use App\User;
use Illuminate\Console\Command;

class UpdateUserSHAP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hxms:update-user-shap {kode_pln_group?} {--skip=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create (if not exist) and Update Data User Komando from data Pegawai SHAP, reset role then add role shap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $nip = $this->argument('nip');
        $kode_pln_group = $this->argument('kode_pln_group');
        // if username is set
        if($kode_pln_group){
            $list_pegawai = PegawaiSHAP::where('kode_pln_group',$kode_pln_group)->orderBy('id','asc')->get();
        }
        else{
            $list_pegawai = PegawaiSHAP::all();
        }

        // dd($list_pegawai->count());

        // create new user from data pegawai shap
        // $list_pegawai = PegawaiSHAP::all();
        $i = 0;
        $email_not_pln = 0;
        $username_null = 0;
        $updated = 0;
        foreach ($list_pegawai as $pegawai) {
            $i++;

            // skip row
            if($i <= $this->option('skip')){
                // $i++;
                continue;
            }

            if($pegawai->username == null || $pegawai->email == null){
                $this->error($i.". [Skip Username/Email Null] ".$pegawai->nip." - ".$pegawai->nama." - ".$pegawai->username." - ".$pegawai->email);
                $username_null++;
                continue;
            }
            
            // if domain email not @pln.co.id, skip
            if(strpos($pegawai->email, '@pln.co.id') === false){
                $this->error($i.". [Skip Email Not @pln.co.id] ".$pegawai->nip." - ".$pegawai->nama." - ".$pegawai->username." - ".$pegawai->email);
                $email_not_pln++;
                continue;
            }

            // cek jika ada user yang INAC set ACTV
            $user = User::where('username', $pegawai->username)->where('status', 'INAC')->first();
            if($user){
                $this->error($i.". [Status INAC] ".$pegawai->nip." - ".$pegawai->nama." - ".$pegawai->username." - ".$pegawai->email);
                $user->status = 'ACTV';
                $user->save();
                // continue;
            }

            //cek jika ada nip sama dan status INAC rename nip
            $user_same_nip = User::where('nip', $pegawai->nip)->where('status', 'INAC')->first();
            if($user_same_nip){
                $this->error($i.". [NIP Same & INAC] ".$pegawai->nip." - ".$pegawai->nama." - ".$pegawai->username." - ".$pegawai->email);
                $user_same_nip->nip = $user_same_nip->nip.'_old';
                $user_same_nip->save();
                // continue;
            }
            
            // check if user exists
            $user = User::where('username', $pegawai->username)->where('status', 'ACTV')->first();

            // if user not exists, create new user
            if (!$user) {
                $user = new User();
                // get last id
                // $last_user = User::orderBy('id', 'desc')->first();
                // $user->id = $last_user->id + 1;
                $this->line($i.". [Creating] ".$pegawai->nip." - ".$pegawai->nama." - ".$pegawai->username." - ".$pegawai->email);

                // cek apakah ada NIP yang sama di users
                $user_same_nip = User::where('nip', $pegawai->nip)->where('status','ACTV')->first();
                if($user_same_nip){
                    $this->error("      [NIP Same] ".$user_same_nip->nip." - ".$user_same_nip->name." - ".$user_same_nip->username." - ".$user_same_nip->email);
                    $user_same_nip->nip = $user_same_nip->nip.'_old';
                    $user_same_nip->save();
                }
            }
            else{
                $this->line($i.". [Updating] ".$user->nip." - ".$user->name." - ".$user->username." - ".$user->email);

                // cek apakah ada NIP yang sama di users
                $user_same_nip = User::where('nip', $pegawai->nip)->where('id', '!=', $user->id)->where('status','!=','ACTV')->first();
                if($user_same_nip){
                    $this->error("      [NIP Same] ".$user_same_nip->nip." - ".$user_same_nip->name." - ".$user_same_nip->username." - ".$user_same_nip->email);
                    $user_same_nip->nip = $user_same_nip->nip.'_old';
                    $user_same_nip->save();
                }

            }

            $user->username = $pegawai->username;
            $user->name = $pegawai->nama;
            $user->email = $pegawai->email;
            $user->password = bcrypt('FreeP@lestin3!');
            $user->active_directory = 1;

            $user->company_code = $pegawai->company_code;
            $user->business_area = $pegawai->business_area;
            // $user->company_code = '1200';
            // $user->business_area = '1201';
            $user->status = 'ACTV';
            $user->domain = 'pusat';
            $user->nip = $pegawai->nip;
            $user->username2 = 'pusat\\' . $pegawai->username;

            $user->holding = 0;
            $user->orgeh = $pegawai->orgeh;
            $user->plans = $pegawai->plans;
            $user->personel_area = $pegawai->personel_area_sap;
            $user->personel_subarea = $pegawai->personel_subarea_sap;

            $bidang = '';
            for($x=15; $x>=1; $x--){
                if($pegawai->{'level_organisasi_'.$x} != null){
                    $bidang = $pegawai->{'level_organisasi_'.$x};
                    break;
                }
            }
            $user->bidang = $bidang;
            $user->jabatan = $pegawai->jabatan;

            $user->save();
            $updated++;

            // cek apakah memiliki role pegawai
            $role_pegawai = Role::where('name', 'pegawai')->first();
            if($user->roles()->where('role_id', $role_pegawai->id)->count() != 0){
                // remove role pegawai
                $user->roles()->detach($role_pegawai->id);
                $this->line("      [Role Pegawai] ".$role_pegawai->name." removed from ".$user->username);
            }
            
            // cek apakah sudah memiliki role shap
            $role = Role::where('name', 'shap')->first();
            if($user->roles()->where('role_id', $role->id)->count() == 0){
                // add role shap
                $user->roles()->attach($role->id);
                $this->line("      [Role SHAP] ".$role->name." added to ".$user->username);
            }
            

            // reset user role
            //$user->roles()->sync([]);

            // attach role shap jika belum ada
            // $role = Role::where('name', 'shap')->first();
            
            // if($user->roles()->where('role_id', $role->id)->count() == 0){
            //     $user->roles()->attach($role->id);
            // }

        }

        $this->info('Data User SHAP has been updated. Updated: '.$updated.', Email not PLN: '.$email_not_pln.', Username null: '.$username_null);
    }
}
