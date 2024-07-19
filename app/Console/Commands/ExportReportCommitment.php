<?php

namespace App\Console\Commands;

use App\BusinessArea;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportReportCommitment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commitment:export {business_area : Business Area} {tahun : Tahun Komitmen} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Export Excel Report Commitment';

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
        $this->line('Export Report Commitment');

        $business_area = $this->argument('business_area');
        $tahun = $this->argument('tahun');

        if ($business_area == ''){
            $this->error('Unit belum dipilih');
            exit;
        }

        if ($tahun == ''){
            $this->error('Tahun belum dipilih');
            exit;
        }
        
        $this->line('Business Area: '.$business_area);
        $this->line('Tahun: '.$tahun);

        $business_area = BusinessArea::where('business_area', $business_area)->first();

        if($business_area == null){
            $this->error('Unit tidak ditemukan');
            exit;
        }

        $user_list = $business_area->users()->where('status', 'ACTV')->orderBy('name', 'asc')->get();

        $filename = date('YmdHis') . '_commitment_' . str_replace(' ', '_', strtolower($business_area->description)) . '_' . $tahun;

        Excel::create($filename, function ($excel) use ($user_list, $business_area, $tahun) {

            $excel->sheet('Commitment', function ($sheet) use ($user_list, $business_area, $tahun) {
                $sheet->loadView('commitment/template_commitment_tahun')
                    ->with('user_list', $user_list)
                    ->with('business_area', $business_area)
                    ->with('tahun', $tahun);
            });
        })->store('xlsx', storage_path('app/public'));
        // })->store(storage_path('app/public/export/text.xlsx'), 'public');
        $domain = env('APP_URL');
        $this->line('URL : https://' . $domain . '/storage/' . $filename . '.xlsx');
        $this->info('Done');
    }
}
