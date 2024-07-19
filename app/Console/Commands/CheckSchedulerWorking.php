<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSchedulerWorking extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'app:check-scheduler';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Check if scheduler works';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $info = 'Ping from scheduler at '.date('d-m-Y H:i:s');
        $this->info($info);
        Log::info($info);
    }
}
