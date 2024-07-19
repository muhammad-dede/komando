<?php

namespace App\Console\Commands;

use App\JabatanSelfAssessment;
use App\JadwalAssessment;
use App\PesertaAssessment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateJabatanPesertaSelfAssessment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'self-assessment:update-jabatan {jadwal_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Jabatan Peserta Self Assessment';

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
        // get $jadwal_id
        $jadwal_id = $this->argument('jadwal_id');

        // get jadwal
        $jadwal = JadwalAssessment::find($jadwal_id);

        $dirkom_id = $jadwal->dirkom_id;

        $list_peserta = PesertaAssessment::where('jadwal_id',$jadwal_id)->orderBy('id','asc')->get();

        $jml_data = 0;
        $jml_updated = 0;

        foreach($list_peserta as $peserta){
            // alter session search incase sensitive
            DB::statement("alter session set NLS_COMP=ANSI");
            DB::statement("alter session set NLS_SORT=BINARY_CI");
            // cek jabatan peserta di master jabatan assessment
            $jabatan_assessment = JabatanSelfAssessment::where('sebutan_jabatan',$peserta->jabatan_pegawai)->where('dirkom_id',$dirkom_id)->first();
            if($jabatan_assessment!=null){
                $this->line($peserta->nama_pegawai." | ".$peserta->jabatan_pegawai." | ".$jabatan_assessment->sebutan_jabatan);
                // update jabatan_id 
                $peserta->jabatan_id = $jabatan_assessment->id;
                $peserta->save();
                $jml_updated++;
            }
            $jml_data++;
        }

        $this->info('Update Finished! '.$jml_updated.' of '.$jml_data);
    }
}
