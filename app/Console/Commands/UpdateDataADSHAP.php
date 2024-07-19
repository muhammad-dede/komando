<?php

namespace App\Console\Commands;

use App\PegawaiSHAP;
use App\User;
use Illuminate\Console\Command;

class UpdateDataADSHAP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hxms:update-data-ad-shap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Data username dan email pegawai SHAP pada table PEGAWAI_SHAP from data Active Directory';

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
        // get data pegawai shap
        // $pegawai = PegawaiSHAP::take(10)->get();
        $pegawai = PegawaiSHAP::all();

        $jml_error = 0;
        $jml_success = 0;
        // update username and email pegawai shap
        foreach ($pegawai as $p) {
            // apakah data ad shap ada
            if(!$p->dataAD){
                $this->error("[Not Found] ".$p->nip." - ".$p->nama);
                $jml_error++;
                continue;
            }

            $p->username = strtolower($p->dataAD->username);
            $p->email = strtolower($p->dataAD->email);
            $p->save();

            $jml_success++;

            // // update user if exist
            // $user = User::where('nip', $p->nip)->first();
            // if($user){
            //     $user->username = $p->username;
            //     $user->email = $p->email;
            //     $user->save();
            // }

            $this->line("[Updated] ".$p->nip." - ".$p->nama." - ".$p->username." - ".$p->email);
        }

        $this->info('Data Success: '.$jml_success.', Data Error: '.$jml_error);

/*
        // get data ad shap
        $csvFile = fopen(base_path('database/data/data_ad_shap.csv'), 'r');
        $firstLine = true;
        // $number = 0;
        // $maxRow = 100;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            // check if pegawai exists
            $pegawai = PegawaiSHAP::where('nip', $row[3])->first();

            // if user exists, update username and email
            if ($pegawai) {
                $pegawai->username = strtolower($row[4]);
                $pegawai->email = strtolower($row[5]);
                $pegawai->save();

                // update user if exist
                $user = User::where('nip', $pegawai->nip)->first();
                if($user){
                    $user->username = $pegawai->username;
                    $user->email = $pegawai->email;
                    $user->save();
                }

                $this->line("[Updated] ".$pegawai->nip." - ".$pegawai->nama." - ".$pegawai->username." - ".$pegawai->email);
            }
            else{
                $this->error("[Not Found] ".$row[3]." - ".$row[4]." - ".$row[5]." - ".$row[1]);
            }

            // $number++;

            // if($number == $maxRow){
            //     break;
            // }
        }
        fclose($csvFile);

        $this->info('Data username dan email pegawai SHAP from data Active Directory has been updated');
        */
    }
}
