<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteLiquidRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'liquid:delete-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all liquid (and their related) data';

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
        \Illuminate\Support\Facades\DB::table('activity_log_book')->delete();
        \Illuminate\Support\Facades\DB::table('feedbacks')->delete();
        \Illuminate\Support\Facades\DB::table('pengukuran_pertama')->delete();
        \Illuminate\Support\Facades\DB::table('pengukuran_kedua')->delete();
        \Illuminate\Support\Facades\DB::table('penyelarasan')->delete();
        \Illuminate\Support\Facades\DB::table('liquid_peserta')->delete();
        \Illuminate\Support\Facades\DB::table('liquid_business_area')->delete();
        \Illuminate\Support\Facades\DB::table('kelebihan_kekurangan_detail')->delete();
        \Illuminate\Support\Facades\DB::table('kelebihan_kekurangan')->delete();
        \Illuminate\Support\Facades\DB::table('liquids')->delete();
        \Illuminate\Support\Facades\DB::table('media')->delete();
    }
}
