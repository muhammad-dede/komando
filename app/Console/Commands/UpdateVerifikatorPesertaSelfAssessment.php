<?php

namespace App\Console\Commands;

use App\PesertaAssessment;
use App\StrukturJabatan;
use App\User;
use Illuminate\Console\Command;

class UpdateVerifikatorPesertaSelfAssessment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'self-assessment:update-verifikator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Data Verifikator Peserta Self Assessment';

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
        $list_peserta = PesertaAssessment::orderBy('id','asc')->get();

        foreach($list_peserta as $peserta){
            // get data strutur jabatan from NIP
            $strukjab = StrukturJabatan::where('nip',$peserta->nip_verifikator)->first();
           
            if($strukjab!=null){
                $this->line($peserta->nama_pegawai." | ".$peserta->nip_verifikator." | ".$strukjab->plans." | ".@$strukjab->strukturPosisi->stext);
                // update jabatan_id 
                $peserta->jabatan_verifikator = @$strukjab->strukturPosisi->stext;
                $peserta->kode_posisi_verifikator = $strukjab->plans;
                $peserta->save();
            }
        }

        $this->info('Update Finished!');
    }
}
