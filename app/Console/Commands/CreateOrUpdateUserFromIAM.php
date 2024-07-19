<?php

namespace App\Console\Commands;

use App\Role;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;

class CreateOrUpdateUserFromIAM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iam:create-or-update-user {nip}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or Update User from IAM by NIP';

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
        // get NIP
        $nip = $this->argument('nip');

        // get access token IAM
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'X-Forwarded-Proto' => 'https'
        ];
        $options = [
            'form_params' => [
            'client_id' => 'dev.pln',
            'client_secret' => 'cjkrflB3iac1kpBZMh1CbGj19nhtFgcJ',
            'provision_key' => 'zIi1UCvVhMm6C2PeKOS76dG5Wzn0AC8T',
            'authenticated_userid' => 'dev.pln',
            'grant_type' => 'password',
            'scope' => 'read'
        ]];
        $request = new Request('POST', 'https://api.pln.co.id/iam/interface/oauth2/token', $headers);
        $res = $client->sendAsync($request, $options)->wait();
        // echo $res->getBody();
        $access_token = json_decode($res->getBody())->access_token;

        // get user data from IAM
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer '.$access_token
        ];
        $request = new Request('GET', 'https://api.pln.co.id/iam/interface/v2/user-nip/'.$nip, $headers);
        $res = $client->sendAsync($request)->wait();

        // if response status code is not 200
        if($res->getStatusCode() != 200){
            $this->error('User not found');
            return;
        }

        $user_iam = json_decode($res->getBody());

        $this->line('User found :'.$user_iam->data->nip.' - '.$user_iam->data->username.' - '.$user_iam->data->fullname.' - '.$user_iam->data->email);


        // check if user exist
        $user = User::where('nip', $nip)->where('status','ACTV')->first();
        
        if($user==null){
            // create user
            $user = new User();
            // $user->nip = $nip;
            // $user->username = $user_data->username;
            // $user->email = $user_data->email;
            // $user->status = 'ACTV';

            $this->info('User Created');
            
        }
        else{
            // update user
            // $user->username = $user_data->username;
            // $user->email = $user_data->email;

            $this->info('User Updated');
        }

        $user->username = strtolower($user_iam->data->username);
        $user->name = strtoupper($user_iam->data->fullname);
        $user->email = strtolower($user_iam->data->email);
        $user->password = bcrypt('FreeP@lestin3!');
        $user->active_directory = 1;

        $user->company_code = $user_iam->data->companyCode->id;
        $user->business_area = $user_iam->data->businessArea->id;
        
        $user->status = 'ACTV';
        $user->domain = 'pusat';
        $user->nip = $user_iam->data->nip;
        $user->username2 = 'pusat\\' . $user_iam->data->username;

        $user->holding = 0;
        $user->orgeh = $user_iam->data->organisasi->id;
        $user->plans = $user_iam->data->posisi->id;
        $user->personel_area = $user_iam->data->personnelArea->id;
        $user->personel_subarea = $user_iam->data->personnelSubArea->id;

        $user->bidang = $user_iam->data->organisasi->name;
        $user->jabatan = $user_iam->data->posisi->name;

        $user->ad_display_name = strtoupper($user_iam->data->fullname);
        $user->ad_mail = strtolower($user_iam->data->email);
        $user->ad_company = $user_iam->data->personnelArea->name;
        $user->ad_department = $user_iam->data->organisasi->name;
        $user->ad_title = $user_iam->data->posisi->name;
        $user->ad_employee_number = $user_iam->data->nip;
        $user->ad_description = $user_iam->data->nip;

        $user->save();

        // cek apakah memiliki role pegawai
        $role_pegawai = Role::where('name', 'pegawai')->first();
        if($user->roles()->where('role_id', $role_pegawai->id)->count() == 0){
            // remove role pegawai
            $user->roles()->attach($role_pegawai->id);
            $this->line("[Role Pegawai] ".$role_pegawai->name." added to ".$user->username);
        }


    }
}
