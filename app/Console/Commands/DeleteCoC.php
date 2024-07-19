<?php

namespace App\Console\Commands;

use App\Coc;
use Illuminate\Console\Command;

class DeleteCoC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coc:cancel {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel CoC Room';

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

        // set status menjadi CANC
        $coc->status = 'CANC';
        $coc->save();
        $this->line('Status CoC CANC.');

        $this->info('CoC berhasil di-cancel.');
    }
}
