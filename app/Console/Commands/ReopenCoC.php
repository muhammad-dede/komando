<?php

namespace App\Console\Commands;

use App\Coc;
use Illuminate\Console\Command;

class ReopenCoC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coc:reopen {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reopen CoC Room';

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
        $this->line('Deleting realisasi CoC...');
        $id = $this->argument('id');

        // get data coc
        $coc = Coc::find($id); 

        // delete realisasi jika ada
        $realisasi = $coc->realisasi;
        // dd($realisasi);
        if($realisasi!=null)
        {
            $realisasi->delete();
            $this->line(' Realisasi CoC deleted.');
        }

        // set status menjadi OPEN
        $coc->status = 'OPEN';
        $coc->save();
        $this->line('Status CoC OPEN.');

        $this->info('CoC berhasil dibuka kembali.');
    }
}
