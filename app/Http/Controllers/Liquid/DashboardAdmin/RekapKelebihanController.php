<?php

namespace App\Http\Controllers\Liquid\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use App\Services\LiquidReportService;
use App\Services\RekapService;
use App\Utils\BasicUtil;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class RekapKelebihanController extends Controller
{
    public function index()
    {
        $config = (new BasicUtil)->getConfig();
        $nav = "kelebihan";
        $companyCode = \request('company_code', auth()->user()->company_code);
        $unitCode = \request('unit_code', auth()->user()->business_area);
        $periode = (int)\request('periode', Carbon::now()->year);

        $liquids = Liquid::query()
            ->forYear($periode)
            ->forCompany($companyCode)
            ->forUnit($unitCode)
            ->get();

        $dataKelebihan = app(LiquidReportService::class)->rekapKelebihan($liquids, request('jabatan'), 5);

        return view('liquid.dashboard-admin.rekap-kelebihan', compact('nav', 'dataKelebihan', 'config'));
    }

    public function download()
    {
        $companyCode = \request('company_code', auth()->user()->company_code);
        $unitCode = \request('unit_code', auth()->user()->business_area);
        $periode = (int)\request('periode', Carbon::now()->year);

        $liquids = Liquid::query()
            ->forYear($periode)
            ->forCompany($companyCode)
            ->forUnit($unitCode)
            ->get();

        $dataKelebihan = app(LiquidReportService::class)->rekapKelebihan($liquids, request('jabatan'), 5);

        $label = app(LiquidReportService::class)
            ->setReportTitleLabel(request());

        Excel::create(
            date('YmdHis').'_liquid_rekap_partisipan_'.$label,
            function ($excel) use ($label, $dataKelebihan) {
                $excel->sheet(str_replace(':', '', str_limit($label, 13)), function ($sheet) use ($label, $dataKelebihan) {
                    $sheet->loadView('report/liquid/liquid_rekap_kelebihan_xls', [
                        'title' => $label,
                        'dataKelebihan' => $dataKelebihan,
                    ]);
                });
            }
        )->download('xlsx');
    }

    public function all()
    {
        $user = auth()->user();

        if (! ($user->hasRole('root') || $user->hasRole('admin_pusat') || $user->hasRole('admin_liquid_pusat'))) {
            return abort(403);
        }

        return (new RekapService)->view('kelebihan');
    }
}
