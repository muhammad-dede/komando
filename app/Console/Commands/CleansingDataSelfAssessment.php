<?php

namespace App\Console\Commands;

use App\JabatanSelfAssessment;
use App\PesertaAssessment;
use Illuminate\Console\Command;

class CleansingDataSelfAssessment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleansing:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleansing data Self Assessment';

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
        $this->info('Cleansing data Self Assessment');
        $this->info('=================================');
        $this->info('1. Check duplicate jabatan di M_JABATAN_SELF_ASMNT');
        // $list_jabatan_self_assessment = JabatanSelfAssessment::where('status', 'ACTV')->where('dirkom_id',2)->orderBy('id', 'asc')->take(100)->get();
        $list_jabatan_self_assessment = JabatanSelfAssessment::where('status', 'ACTV')->where('dirkom_id',2)->orderBy('id', 'asc')->get();
        // dd($list_jabatan_self_assessment->count());
        foreach ($list_jabatan_self_assessment as $jabatan_self_assessment) {
            // $this->info('Jabatan Self Assessment: '.$jabatan_self_assessment->sebutan_jabatan);
            // cek duplikasi
            $count_duplicate = JabatanSelfAssessment::where('sebutan_jabatan', $jabatan_self_assessment->sebutan_jabatan)
                ->where('organisasi', $jabatan_self_assessment->organisasi)
                ->where('status', 'ACTV')
                ->where('dirkom_id',2)
                ->get();
            $jml = $count_duplicate->count();
            if ($jml > 1) {
                $this->line('ID: '.$jabatan_self_assessment->id);
                $this->line('Jabatan: '.$jabatan_self_assessment->sebutan_jabatan);
                $this->line('Organisasi: '.$jabatan_self_assessment->organisasi);
                $this->line('Jumlah duplikasi: '.$jml);
                $this->line('=================================');
                $this->info('   Delete duplicate jabatan');

                $skip = true;
                foreach($count_duplicate as $duplicate) {
                    if ($skip) {
                        // get first id 
                        $first_id = $duplicate->id;
                        $skip = false;
                        continue;
                    }

                    $this->line('   ID: '.$duplicate->id);
                    $this->line('   Jabatan: '.$duplicate->sebutan_jabatan);
                    $this->line('   Organisasi: '.$duplicate->organisasi);
                    $this->line('   =================================');

                    // cek peserta dengan jabatan duplicate
                    $peserta = PesertaAssessment::where('jabatan_id', $duplicate->id)->get();
                    if ($peserta->count() > 0) {
                        $this->info('   Update peserta dengan jabatan duplicate');
                        foreach ($peserta as $p) {
                            
                            $this->line('      NIP: '.$p->nip_pegawai);
                            $this->line('      Nama: '.$p->nama_pegawai);
                            $this->line('      =================================');

                            $p->jabatan_id = $first_id;
                            // $p->save();
                        }
                    }

                    $this->info('   Delete jabatan yang duplikat');
                    // delete duplicate
                    $duplicate->delete();
                    $this->info('=================================');
                }

            }
        }
        
        $this->info('Cleansing data Self Assessment selesai');
    }
}
