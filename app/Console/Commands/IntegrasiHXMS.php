<?php

namespace App\Console\Commands;

use App\GroupPLN;
use App\InterfaceLog;
use App\PegawaiSHAP;
use App\PegawaiSHAPAD;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;

class IntegrasiHXMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hxms:get-data-shap {kode_pln_group} {page?} {size?} {--get-all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Data Pegawai SHAP Per PLN Group From API HXMS';
    protected $base_url;
    protected $access_token;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->base_url = env('HXMS_BASE_URL','http://10.1.85.207:7071/api');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->access_token = $this->getAccessToken();
        try{
            // get kode_pln_group from argument
            $kode_pln_group = $this->argument('kode_pln_group');
            $page = $this->argument('page');
            $size = $this->argument('size');
            
            // get get_all from option
            $get_all = $this->option('get-all');

            // if page not set
            if(!$page){
                $page = 0;
            }
            
            // if size not set
            if(!$size){
                $size = 10;
            }

            // get description from kode_pln_group
            $description = GroupPLN::where('kode', $kode_pln_group)->first()->description;

            // if kode_pln_group not found
            if(!$description){
                InterfaceLog::log('Interface HXMS', 'Get Data Pegawai SH/AP HXMS Failed! Kode PLN Group '.$kode_pln_group.' not found.', 'ERROR');
                $this->error('Kode PLN Group '.$kode_pln_group.' not found.');
                return;
            }

            $data_hxms = $this->getDataPegawai($description, $page, $size);

            // get pagination info
            // $first = $data->first;
            // $last = $data->last;
            $total_page = $data_hxms->totalPages;
            $total_elements = $data_hxms->totalElements;
            // $number_of_elements = $data->numberOfElements;
            // $size = $data_hxms->size;
            $number = $data_hxms->number;
            // $empty = $data->empty;

            $this->storeDataPegawai($data_hxms);
            
            if($get_all){
                while($number < $total_page){
                    $number = $number + 1;
                    $this->info('Next Page: '.$number.' of '.$total_page.' ......... ');

                    // get next page
                    $data_hxms = $this->getDataPegawai($description, $number, $size);
                    $this->storeDataPegawai($data_hxms);
                }
            }

            $this->info('FINISHED for '.$description.'. Total data: '.$total_elements.', Page: '.$number.' of '.$total_page);
        
            InterfaceLog::log('Interface HXMS', 'Get Data Pegawai SH/AP HXMS Success. Total data: '.$total_elements.', Page: '.$number.' of '.$total_page, 'FINISHED');
        }
        catch (\Exception $e) {
            InterfaceLog::log('Interface HXMS', 'Get Data Pegawai SH/AP HXMS Failed! ' . $e->getMessage(), 'ERROR');
            $this->error($e->getMessage());
            return;
        }
    }

    public function getDataPegawai($description, $page, $size){
        $access_token = $this->access_token;

        $client = new Client();
        $headers = [
                        'Authorization' => 'Bearer '.$access_token,
                    ];
        
        $request = new Request('GET', $this->base_url.'/data-pegawai-aktif/page-pegawai-komando?page='.$page.'&size='.$size.'&plnGroup='.$description, $headers);
        $res = $client->sendAsync($request)->wait();
        $body = json_decode($res->getBody());
        $status = $body->status;
        if($status != '200'){
            InterfaceLog::log('Interface HXMS', 'Get Data Pegawai SH/AP HXMS Failed! ' . $body->message, 'ERROR');
            $this->error($body->message);
            return;
        }

        // if $body->data is empty
        if(empty($body->data)){
            InterfaceLog::log('Interface HXMS', 'Get Data Pegawai SH/AP HXMS Success. Data Pegawai SH/AP HXMS is empty.', 'INFO');
            $this->info('Data Pegawai SH/AP HXMS is empty.');
            return;
        }

        $data = $body->data;

        return $data;
    }

    public function storeDataPegawai($data_hxms){
        // $data_hxms = $this->getDataPegawai($description, $page, $size);
        $data_pegawai = $data_hxms->content;

        $x = 1;
        // store data pegawai
        foreach($data_pegawai as $data){

            // jika data email atau username kosong, skip
            // if($data->email == null || $data->username == null){
            //     $this->error('[Error] '.$data->nip.' | '.$data->username.' | '.$data->email.' | '.$data->nama);
            //     // continue;
            // }
            
            // cek apakah sudah ada data pegawai di table pegawai_shap
            $pegawai = PegawaiSHAP::where('nip', $data->nip)->first();

            // jika belum ada, create object pegawai
            if($pegawai==null){
                $pegawai = new PegawaiSHAP();
                $this->line($x++.'. [Created] '.$data->nip.' | '.$data->username.' | '.$data->email.' | '.$data->nama);
            }
            else{
                $this->line($x++.'.[Updated] '. $data->nip.' | '.$data->username.' | '.$data->email.' | '.$data->nama);
            }

            // set data pegawai
            $pegawai->nip = $data->nip;

            // search username dan email dari data PegawaiSHAPAD
            $pegawaiAD = PegawaiSHAPAD::where('nip', $data->nip)->first();

            if($pegawaiAD){
                $pegawai->username = strtolower($pegawaiAD->username);
                $pegawai->email = strtolower($pegawaiAD->email);
                $this->line('   [Found AD] '.$pegawaiAD->username.' | '.$pegawaiAD->email);
            }
            else{
                $pegawai->username = strtolower($data->username);
                $pegawai->email = strtolower($data->email);
            }

            $pegawai->nama = $data->nama;
            $pegawai->tempat_lahir = $data->tempatLahir;
            $pegawai->tanggal_lahir = Carbon::parse($data->tanggalLahir);
            $pegawai->alamat = $data->alamat;
            $pegawai->no_telpon = $data->noTelepon;
            $pegawai->agama = $data->agama;
            $pegawai->jenis_kelamin = $data->jenisKelamin;
            $pegawai->status_nikah = $data->statusNikah;

            $pegawai->nip_atasan = $data->nipAtasan;
            $pegawai->nama_atasan = $data->namaAtasan;
            $pegawai->jabatan_atasan = $data->jabatanAtasan;

            $pegawai->kode_pln_group = $data->kodePlnGroup;
            $pegawai->pln_group = $data->plnGroup;
            $pegawai->ee_group = $data->eeGroup;
            $pegawai->ee_sub_group = $data->eeSubGroup;

            $pegawai->job_key = $data->jobKey;
            $pegawai->jabatan = $data->jabatan;
            $pegawai->jenis_jabatan = $data->jenisJabatan;
            $pegawai->jenjang_jabatan = $data->jenjangJabatan;
            $pegawai->kode_profesi = $data->kodeProfesi;
            $pegawai->nama_profesi = $data->namaProfesi;

            $pegawai->jenis_unit = $data->jenisUnit;
            $pegawai->kelas_unit = $data->kelasUnit;
            $pegawai->kode_daerah = $data->kodeDaerah;
            $pegawai->kota_organisasi = $data->kotaOrganisasi;

            $pegawai->company_code = $data->companyCode;
            $pegawai->business_area = $data->businessArea;
            $pegawai->personel_area = $data->personalArea;
            $pegawai->personel_sub_area = $data->personalSubArea;

            $pegawai->stream_business = $data->streamBusiness;
            $pegawai->kode_posisi = $data->kodePosisi;
            $pegawai->grade = $data->grade;

            $pegawai->level_organisasi_1 = $data->levelOrg1;
            $pegawai->level_organisasi_2 = $data->levelOrg2;
            $pegawai->level_organisasi_3 = $data->levelOrg3;
            $pegawai->level_organisasi_4 = $data->levelOrg4;
            $pegawai->level_organisasi_5 = $data->levelOrg5;
            $pegawai->level_organisasi_6 = $data->levelOrg6;
            $pegawai->level_organisasi_7 = $data->levelOrg7;
            $pegawai->level_organisasi_8 = $data->levelOrg8;
            $pegawai->level_organisasi_9 = $data->levelOrg9;
            $pegawai->level_organisasi_10 = $data->levelOrg10;
            $pegawai->level_organisasi_11 = $data->levelOrg11;
            $pegawai->level_organisasi_12 = $data->levelOrg12;
            $pegawai->level_organisasi_13 = $data->levelOrg13;
            $pegawai->level_organisasi_14 = $data->levelOrg14;
            $pegawai->level_organisasi_15 = $data->levelOrg15;

            // get data pln group
            $pln_group = GroupPLN::where('kode', $data->kodePlnGroup)->first();
            if($pln_group){
                $pegawai->company_code = $pln_group->company_code;
                $pegawai->business_area = $pln_group->business_area;
                $pegawai->orgeh = $pln_group->orgeh;
                $pegawai->personel_area_sap = $pln_group->personel_area;
                $pegawai->personel_subarea_sap = $pln_group->personel_subarea;
            }

            $pegawai->save();

            
        }
    }

    private function getAccessToken()
    {
        try{
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json'
            ];
            $username = env('HXMS_USERNAME', 'komando_app');
            $password = env('HXMS_PASSWORD', 'password_komando');
            
            $body = '{
                    "username": "'.$username.'",
                    "password": "'.$password.'"
                    }';
            $request = new Request('POST', $this->base_url.'/auth/login', $headers, $body);
            $res = $client->sendAsync($request)->wait();

            return json_decode($res->getBody())->jwtToken;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
