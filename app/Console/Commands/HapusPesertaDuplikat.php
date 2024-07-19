<?php

namespace App\Console\Commands;

use App\Models\Liquid\LiquidPeserta;
use Illuminate\Console\Command;

class HapusPesertaDuplikat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liquid:hapus-peserta-duplikat {liquid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus peserta liquid (pasangan atasan-bawahan) yang duplikat';

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
        $liquidId = $this->argument('liquid');
        $pesertaGrouped = LiquidPeserta::where('liquid_id', $liquidId)->get()->groupBy(function ($item) {
            return sprintf('%s-%s-%s', $item->liquid_id, $item->atasan_id, $item->bawahan_id);
        });

        $deleted = 0;
        foreach ($pesertaGrouped as $peserta) {
            if (count($peserta) > 1) {
                // selamatkan peserta pertama, hapus sisanya
                $peserta->pop();
                foreach ($peserta as $item) {
                    $item->delete();
                    $deleted++;
                }
            }
        }

        $this->info('Peserta dihapus: '.$deleted);
    }
}
