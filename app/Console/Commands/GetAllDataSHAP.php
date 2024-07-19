<?php

namespace App\Console\Commands;

use App\GroupPLN;
use Illuminate\Console\Command;

class GetAllDataSHAP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hxms:get-all-pegawai-shap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get All Data Pegawai SHAP From API HXMS';

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
        // get all PLN Group
        $pln_groups = GroupPLN::whereIn('company_code',['1200','1300'])->get();

        foreach ($pln_groups as $pln_group) {
            $this->line('Get Data SHAP for '.$pln_group->description.'....');
            $this->call('hxms:get-data-shap', [
                'kode_pln_group' => $pln_group->kode,
                'page' => 1,
                'size' => 100,
                '--get-all' => true
            ]);
        }

        $this->info('Get All Data Pegawai SHAP Success');
    }
}
