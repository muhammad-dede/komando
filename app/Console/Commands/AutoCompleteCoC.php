<?php

namespace App\Console\Commands;

use App\Autocomplete;
use App\Coc;
use App\RealisasiCoc;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCompleteCoC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coc:autocomplete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Autocomplete CoC';

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
        $date_seminggu_lalu = Carbon::now()->subDays(env('SUBDAYS_AUTOCOMPLETE',5))->format('Y-m-d');

        $this->line('Date : '.$date_seminggu_lalu);
        // get room CoC < H-5

        $list_coc_open = Coc::where('status','OPEN')
            ->whereDate('tanggal_jam','<=',$date_seminggu_lalu)
            ->whereNotNull('nip_leader')
            ->orderBy('tanggal_jam', 'desc')
            // ->take(10)
            ->get();
            
        // complete CoC
        foreach ($list_coc_open as $data) {
            $coc = $data;
            $pernr_leader = $coc->pernr_leader;
            $tanggal = $coc->tanggal_jam->format('Ymd');

            $coc->status = 'COMP';
            $coc->autocomplete = 1;
            $coc->save();

            if($coc->plans_leader=='ERR'){
                echo 'EAC-001 :'.$coc->id.'<br>';
                Autocomplete::log('ERROR', 'EAC001 : Jenjang leader tidak ditemukan di sistem.', $coc->id, $coc->tanggal_jam, 0);
                continue;
            }

            $realisasi = new RealisasiCoc();
            $realisasi->coc_id = $coc->id;

            $realisasi->level = $coc->level_unit;
            $realisasi->jenjang_id = $coc->jenjang_id;

            $realisasi->pernr_leader = $coc->pernr_leader;
            $realisasi->nip_leader = $coc->nip_leader;
            
            $realisasi->business_area = $coc->business_area;
            $realisasi->company_code = $coc->company_code;
            $realisasi->realisasi = $coc->tanggal_jam;

            $realisasi->orgeh = $coc->orgeh;
            $realisasi->plans = $coc->plans_leader;
            $realisasi->delegation = $coc->delegation_leader;
            $realisasi->autocomplete = 1;

            $realisasi->save();

            $this->line('Complete CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id);
            Autocomplete::log('SUCCESS', 'Complete CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, $coc->id, $coc->tanggal_jam, $realisasi->id);

        }

        $this->info('FINISHED');
    }
}
