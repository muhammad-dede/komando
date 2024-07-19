<?php

namespace App\Console\Commands;

use App\Role;
use App\User;
use Illuminate\Console\Command;

class ResetRoleSHAP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hxms:reset-role-shap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset role user SHAP to Pegawai';

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
        // get data ad shap
        $csvFile = fopen(base_path('database/data/data_reset_role_shap.csv'), 'r');
        $firstLine = true;
        $number = 0;
        $maxRow = 10;

        $role_pegawai = Role::where('name', 'pegawai')->first();

        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }

            $nip = strtoupper($row[3]);
            $username = strtolower($row[4]);
            $email = strtolower($row[5]);
            $nama = strtoupper($row[1]);
            $bidang = $row[7];
            $ou_name = $row[8];
            $jabatan = $row[11];
            $company = $row[9];

            // if username or email is empty, skip
            if($username == '' || $email == ''){
                continue;
            }


            // dd($nip, $username, $email, $nama, $bidang, $ou_name, $jabatan);
            
            // check if user exists from username
            $user = User::where('username', $username)->where('status','ACTV')->first();

            // jika user ditemukan, reset role
            if($user != null){

                $user->holding = 1;
                $user->save();

                // // reset user role
                $user->roles()->sync([]);

                // // attach role pegawai
                $user->roles()->attach($role_pegawai->id);

                $this->line($user->nip.' | '.$user->username.' | '.$user->email.' | '.$user->name.' | '.$user->status);

            }

            // $number++;

            // if($number == $maxRow){
            //     break;
            // }
        }
        fclose($csvFile);

        $this->info('Role pegawai has been updated');
    }
}
