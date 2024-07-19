<?php

namespace App\Console\Commands;

use App\PegawaiSHAP;
use App\Role;
use App\User;
use Illuminate\Console\Command;

class CreateUserSHAPFromAD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hxms:create-user-shap-ad {filename} {--limit=10} {--skip=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create User if Not Exist and Update Data SHAP from Active Directory';

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
        // get filename 
        $file = $this->argument('filename');

        // get skip
        $skip = $this->option('skip');

        if($file == null){
            $file = 'data_shap_filter.csv';
        }

        $this->info('Processing data from '.$file.'...');

        // get data ad shap
        $csvFile = fopen(base_path('database/data/'.$file), 'r');
        $firstLine = true;
        $number = 0;
        $maxRow = $this->option('limit');

        if($maxRow == null){
            $maxRow = 10;
        }

        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }

            // skip data
            if($number < $skip){
                $number++;
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
            
            // check if pegawai exists from nip
            $pegawai = PegawaiSHAP::where('nip', $nip)->first();

            // if user exists, update username and email
            if ($pegawai != null) {
                $pegawai->username = strtolower($username);
                $pegawai->email = strtolower($email);
                $pegawai->save();

                $this->line("[Updated Data Pegawai] ".$pegawai->nip." - ".$pegawai->nama." - ".$pegawai->username." - ".$pegawai->email);
            }
            else{
                $this->error("[Not Found in Data Pegawai] ".$nip." - ".$username." - ".$email." - ".$nama);
            }

            // check if user exist but status not active, update status to ACTV
            $user_check = User::where('username', $username)->where('status','!=','ACTV')->first();
            if($user_check!=null){
                $user_check->status = 'ACTV';
                $user_check->save();
                $this->line("[Updated Status User] ".$nip." - ".$username." - ".$email." - ".$nama);
            }

            // check if user exists from username
            $user = User::where('username', $username)->where('status','ACTV')->first();

            if($user == null){
                $user = new User();
                // get last id
                // $last_id = User::orderBy('id', 'desc')->take(1)->first();
                // $user->id = $last_id->id+1;
                $this->line("[Created Data User] ".$nip." - ".$username." - ".$email." - ".$nama);
            }
            else{
                $this->line("[Updated Data User] ".$nip." - ".$username." - ".$email." - ".$nama);
            }

            $user->username = $username;
            $user->name = $nama;
            $user->email = $email;
            $user->password = bcrypt('FreeP@lestin3!');
            $user->active_directory = 1;

            $user->ad_display_name = $nama;
            $user->ad_mail = $email;
            $user->ad_company = $company;
            $user->ad_department = $bidang;
            $user->ad_title = $jabatan;
            $user->ad_employee_number = $nip;
            $user->ad_description = $nip;

            // get company code and business area from data pegawai shap
            if($pegawai != null){
                $user->company_code = @$pegawai->company_code;
                $user->business_area = @$pegawai->business_area;
            }
            // get company code and business area from data AD
            else{
                // if Sub Holding
                if($ou_name == 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN IP' || 
                    $ou_name == 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN NP' || 
                    $ou_name == 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN ICON PLUS' || 
                    $ou_name == 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN EPI' )
                {
                    $user->company_code = '1200';
                    $user->business_area = '1201';
                }
                elseif($ou_name == 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN BATAM' || 
                    $ou_name == 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN ENJINIRING' || 
                    $ou_name == 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN NUSA DAYA' ||
                    $ou_name == 'PLN SUBHOLDING ANAK PERUSAHAAN/PT HP' ||
                    $ou_name == 'PLN SUBHOLDING ANAK PERUSAHAAN/PT EMI' ||
                    $ou_name == 'PLN SUBHOLDING ANAK PERUSAHAAN/PT MCTN')
                {
                    // if Anak Perusahaan
                    $user->company_code = '1300';
                    $user->business_area = '1301';
                }
                else{
                    $user->company_code = '';
                    $user->business_area = '';
                }
            }
            $user->status = 'ACTV';
            $user->domain = 'pusat';
            $user->nip = $nip;
            $user->username2 = 'pusat\\' . $username;

            $user->holding = 0;

            // get data employee from data pegawai shap
            if($pegawai != null){
                $user->orgeh = @$pegawai->orgeh;
                $user->plans = @$pegawai->plans;
                $user->personel_area = @$pegawai->personel_area_sap;
                $user->personel_subarea = @$pegawai->personel_subarea_sap;
                $user->bidang = @$pegawai->personel_area;
                $user->jabatan = @$pegawai->jabatan;
            }
            // get cdata employee from data AD
            else{
                // get orgeh from data AD
                switch ($ou_name) {
                    case 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN IP':
                        $user->orgeh = '10096380';
                        break;
                    case 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN NP':
                        $user->orgeh = '10096381';
                        break;
                    case 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN ICON PLUS':
                        $user->orgeh = '10096382';
                        break;
                    case 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN EPI':
                        $user->orgeh = '10096383';
                        break;
                    
                    case 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN BATAM':
                        $user->orgeh = '10091862';
                        break;
                    case 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN ENJINIRING':
                        $user->orgeh = '17409295';
                        break;
                    case 'PLN SUBHOLDING ANAK PERUSAHAAN/PLN NUSA DAYA':
                        $user->orgeh = '17499451';
                        break;
                    case 'PLN SUBHOLDING ANAK PERUSAHAAN/PT HP':
                        $user->orgeh = '17408000';
                        break;
                    case 'PLN SUBHOLDING ANAK PERUSAHAAN/PT EMI':
                        $user->orgeh = '10095429';
                        break;
                    case 'PLN SUBHOLDING ANAK PERUSAHAAN/PT MCTN':
                        $user->orgeh = '10095320';
                        break;
                    
                    default:
                        $user->orgeh = '';
                        break;
                }
                $user->plans = '';
                $user->personel_area = '';
                $user->personel_subarea = '';
                $user->bidang = $bidang;
                $user->jabatan = $jabatan;
            }

            $user->save();

            // reset user role
            //$user->roles()->sync([]);

            // attach role shap jika belum ada
            $role = Role::where('name', 'shap')->first();
            
            if($user->roles()->where('role_id', $role->id)->count() == 0){
                $user->roles()->attach($role->id);
            }

            $number++;

            if($number == $maxRow){
                break;
            }
        }
        fclose($csvFile);

        $this->info('Data username dan email pegawai SHAP from data Active Directory has been updated');
    }
}
