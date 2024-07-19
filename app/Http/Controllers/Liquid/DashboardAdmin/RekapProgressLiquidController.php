<?php

namespace App\Http\Controllers\Liquid\DashboardAdmin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use Carbon\Carbon;
use App\Services\LiquidReportService;
use Maatwebsite\Excel\Facades\Excel;

class RekapProgressLiquidController extends Controller
{
    public function index()
    {
        $nav = "all-rekap-progress-liquid";
        $user = auth()->user();
        $user_CompanyCode = "";
        if(empty($user->company_code)) {
            return redirect()->back()->with('error', 'Tidak Ada Akses. Company Code tidak ditemukan. Tolong Hubungi Admin.');
        }
        $user_CompanyCode = $user->company_code;

        $user_BusinessArea = "";
        if(empty($user->business_area)) {
            return redirect()->back()->with('error', 'Tidak Ada Akses. Business Area tidak ditemukan. Tolong Hubungi Admin.');
        }
        $user_BusinessArea = $user->business_area;
        
        $companyCode = \request('company_code', $user_CompanyCode);
        $unitCode = \request('unit_code', $user_BusinessArea);
        $periode = (int)\request('periode', Carbon::now()->year);
        $divisi = request('divisi', $user->getKodeDivisiPusat());

        $liquids = Liquid::query()
            ->published()
            ->forYear($periode)
            ->forCompany($companyCode)
            ->forUnit($unitCode)
            ->get();
        $dataProgressLiquid = app(LiquidReportService::class)->rekapProgressLiquid($liquids, $unitCode, request('jabatan'), $divisi, 0, 5);

        return view('liquid.dashboard-admin.rekap-progress-liquid', compact('nav', 'user', 'companyCode', 'dataProgressLiquid'));
    }

    public function download()
    {
        $user = auth()->user();
        $companyCode = \request('company_code', auth()->user()->company_code);
        $unitCode = \request('unit_code', auth()->user()->business_area);
        $periode = (int)\request('periode', Carbon::now()->year);
        $divisi = request('divisi', $user->getKodeDivisiPusat());

        $liquids = Liquid::query()
            ->published()
            ->forYear($periode)
            ->forCompany($companyCode)
            ->forUnit($unitCode)
            ->get();
        $dataProgressLiquid = app(LiquidReportService::class)->rekapProgressLiquid($liquids, $unitCode, request('jabatan'), $divisi, 0, 5);

        $label = app(LiquidReportService::class)
            ->setReportTitleLabelProgressLiquid(request(), $divisi);

        Excel::create(
            date('YmdHis').'_rekap_progress_liquid_'.$label,
            function ($excel) use ($label, $dataProgressLiquid) {
                $excel->sheet(str_replace(':', '', str_limit($label, 13)), function ($sheet) use ($label, $dataProgressLiquid) {
                    $sheet->loadView('report/liquid/liquid_rekap_progress_xls', [
                        'title' => $label,
                        'dataProgressLiquid' => $dataProgressLiquid,
                    ]);
                });
            }
        )->download('xlsx');
    }
}
