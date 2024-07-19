<?php

namespace App\Http\Controllers\Liquid\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use App\Services\LiquidReportService;
use App\Services\RekapService;
use App\Utils\BasicUtil;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class RekapKekuranganController extends Controller
{
    public function index()
    {
        $config = (new BasicUtil)->getConfig();
        $nav = "kekurangan";
        $companyCode = \request('company_code', auth()->user()->company_code);
        $unitCode = \request('unit_code', auth()->user()->business_area);
        $periode = (int) \request('periode', Carbon::now()->year);

        $liquids = Liquid::query()
            ->forYear($periode)
            ->forCompany($companyCode)
            ->forUnit($unitCode)
            ->get();

        $dataKekurangan = app(LiquidReportService::class)->rekapKekurangan($liquids, request('jabatan'), 5);

        return view('liquid.dashboard-admin.rekap-kekurangan', compact('nav', 'dataKekurangan', 'config'));
    }

    public function download()
    {
        $companyCode = \request('company_code', auth()->user()->company_code);
        $unitCode = \request('unit_code', auth()->user()->business_area);
        $periode = (int) \request('periode', Carbon::now()->year);

        $liquids = Liquid::query()
            ->forYear($periode)
            ->forCompany($companyCode)
            ->forUnit($unitCode)
            ->get();

        $dataKekurangan = app(LiquidReportService::class)->rekapKekurangan($liquids, request('jabatan'), 5);

        $label = app(LiquidReportService::class)
            ->setReportTitleLabel(request());

        Excel::create(
            date('YmdHis').'_liquid_rekap_partisipan_'.$label,
            function ($excel) use ($label, $dataKekurangan) {
                $excel->sheet(str_replace(':', '', str_limit($label, 13)), function ($sheet) use ($label, $dataKekurangan) {
                    $sheet->loadView('report/liquid/liquid_rekap_kekurangan_xls', [
                        'title' => $label,
                        'dataKekurangan' => $dataKekurangan,
                    ]);
                });
            }
        )->download('xlsx');
    }

    public function all()
    {
        return (new RekapService)->view('kekurangan');
    }
}
