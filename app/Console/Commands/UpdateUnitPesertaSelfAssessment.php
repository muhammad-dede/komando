<?php

namespace App\Console\Commands;

use App\JabatanSelfAssessment;
use App\PesertaAssessment;
use App\StrukturJabatan;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateUnitPesertaSelfAssessment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'self-assessment:update-unit {jadwal_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Unit & Kode Posisi Peserta Self Assessment';

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

        $jadwal_id = $this->argument('jadwal_id');

        $list_peserta = PesertaAssessment::where('jadwal_id',$jadwal_id)->orderBy('id','asc')->get(); 

        $jml_data = 0;
        $jml_updated = 0;
        foreach($list_peserta as $peserta){
            // get data strutur jabatan from NIP
            $strukjab = StrukturJabatan::where('nip',$peserta->nip_pegawai)->first();
            // get data user
            $user = User::where('nip', $peserta->nip_pegawai)->first();

            if($strukjab!=null){
                $this->line($peserta->nama_pegawai." | ".$peserta->jabatan_pegawai." | ".$strukjab->plans);
                // update jabatan_id 
                $peserta->kode_posisi = $strukjab->plans;
                $peserta->save();
            }

            if($user!=null){
                $this->line($peserta->nama_pegawai." | ".$peserta->jabatan_pegawai." |  ".$user->business_area);
                // update jabatan_id 
                $peserta->company_code = $user->company_code;
                $peserta->business_area = $user->business_area;
                $peserta->save();
                $jml_updated++;
            }

            $jml_data++;
        }

        $this->info('Update Finished! '.$jml_updated.' of '.$jml_data);
    }
}
