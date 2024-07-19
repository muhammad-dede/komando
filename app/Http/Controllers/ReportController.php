<?php

namespace App\Http\Controllers;

use App\BusinessArea;
use App\Coc;
use App\CompanyCode;
use App\JenisCoc;
use App\JenjangJabatan;
use App\RealisasiCoc;
use App\Role;
use App\Services\Datatable;
use App\Http\Requests;
use App\Materi;
use App\StrukturOrganisasi;
use App\TargetCoc;
use App\TemaCoc;
use App\UnitMonitoring;
use App\User;
use App\Utils\BusinessAreaUtil;
use App\Utils\CocUtil;
use App\Utils\CompanyCodeUtil;
use App\Utils\UnitKerjaUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function historyCoC(){
        return view('report.history_coc');
    }

    public function exportHistoryCoc()
    {
        $attendants = Auth::user()->attendant()->orderBy('id', 'desc')->get();

        Excel::create(date('YmdHis').'_history_coc_' . Auth::user()->nip, function ($excel) use ($attendants) {

            $excel->sheet('History CoC', function ($sheet) use ($attendants) {
                $sheet->loadView('report/template_history_coc')->with('attendants', $attendants);
            });

        })->download('xlsx');
//        })->download('xls');
    }

    public function temaCoC(){
        ini_set('max_execution_time', 500);

        $query = TemaCoc::query()->with('tema');

        $datatable = Datatable::make($query)
            ->rowView('report.tema_coc_row')
            ->search(function ($builder, $keyword) {
                $builder->whereHas('tema', function ($q2) use ($keyword) {
                    $q2->where(DB::raw('lower(tema)'), 'like', "%$keyword%");
                });
            })
            ->columns([
                ['data' => 'tema'],
                ['data' => 'start_date'],
                ['data' => 'end_date'],
                ['data' => 'count'],
            ]);

        if (\request()->wantsJson()) {
            return $datatable->toJson();
        }

        return view('report.tema_coc', compact('datatable'));
    }

    public function exportTemaCoc()
    {
//        $attendants = Auth::user()->attendant()->orderBy('id', 'desc')->get();
        $tema_coc = TemaCoc::all();

        Excel::create(date('YmdHis').'_tema_coc', function ($excel) use ($tema_coc) {

            $excel->sheet('Tema CoC', function ($sheet) use ($tema_coc) {
                $sheet->loadView('report/template_tema_coc')->with('tema_coc', $tema_coc
                );
            });

        })->download('xlsx');
//        })->download('xls');
    }

//    public function briefingCoC()
//    {
//        $realisasi_list = RealisasiCoc::all();
//
//        return view('report.briefing_coc', compact('realisasi_list'));
//    }

    public function briefingCoc()
    {
        $tgl = Date::now();
        $startDate = \request('start_date', Date::parse($tgl->format('d-m-Y'))->format('d-m-Y'));
        $endDate = \request('end_date', Date::parse($tgl->format('d-m-Y'))->format('d-m-Y'));;
        $cc_selected = request('company_code');

        $user = auth()->user();
        $cc_selected = $user->company_code;
        $resultShifting = (new UnitKerjaUtil)->shiftingCompanyCode($user);

        if (empty($cc_selected)) {
            $cc_selected = $resultShifting;
        } else {
            $cc_selected = [$cc_selected];
        }

        $coCodeList = (new CompanyCodeUtil)->generateOptions($user, $resultShifting);

        $cc_selected = $cc_selected[0];

        return view('report.briefing_coc', compact('coCodeList', 'cc_selected', 'startDate', 'endDate'));
    }

    public function searchResultBriefing(Request $request)
    {
        $startDate = Date::parse($request->start_date)->format('d-m-Y');
        $endDate = Date::parse($request->end_date)->format('d-m-Y');

        if (empty($request->company_code)) {
            return redirect('report/briefing-coc')->with('warning', 'Company Code belum dipilih');
        }

        $cc_selected = $request->company_code;
        $user = auth()->user();
        $companyCode = [];

        if (empty($cc_selected)) {
            $companyCode = (new UnitKerjaUtil)->shiftingCompanyCode($user);
        }

        $coCodeList = (new CompanyCodeUtil())->getAll()->toSelectOptions($user, $companyCode);

        return view('report.briefing_coc', compact('coCodeList', 'cc_selected', 'startDate', 'endDate'));
    }

    public function exportBriefingCoc($company_code, $start_date, $end_date)
    {
        if($company_code==0)
            return redirect('report/briefing-coc')->with('warning','Company Code belum dipilih');

        $tgl_awal = Date::parse($start_date);
        $tgl_akhir = Date::parse($end_date);

        $company_code = CompanyCode::where('company_code', $company_code)->first();
        $cc_selected = $company_code;
//        $realisasi_list = $company_code->realisasi;
        $realisasi_list = $company_code->realisasi()
            ->whereDate('realisasi', '>=', $tgl_awal->format('Y-m-d'))
            ->whereDate('realisasi', '<=', $tgl_akhir->format('Y-m-d'))
            ->orderBy('realisasi', 'asc')
            ->get();

        Excel::create(date('YmdHis').'_briefing_coc', function ($excel) use ($cc_selected, $realisasi_list) {

            $excel->sheet('Briefing CoC', function ($sheet) use ($cc_selected, $realisasi_list) {
                $sheet->loadView('report/template_briefing_coc', ['cc_selected'=>$cc_selected, 'realisasi_list'=>$realisasi_list]);
            });

        })->download('xlsx');
//        })->download('xls');
    }

    public function rekapCoC()
    {
        $user = Auth::user();
        $tgl_awal = Date::parse(date('Y').'-01-01');
        $tgl_akhir = Date::now();

        $cc_selected = request('company_code');
        $resultShifting = (new UnitKerjaUtil)->shiftingCompanyCode($user);

        if (empty($cc_selected)) {
            $cc_selected = $user->company_code;
        }

        $coCodeList = (new CompanyCodeUtil)->generateOptions($user, $resultShifting);

        $jenjang_list = JenjangJabatan::where('id','!=','7')->orderBy('id','asc')->get();

        // GET JML PEJABAT
        $arr_jml = [];
        for($x=1;$x<=6;$x++) {
            $arr_jml[$x] =  JenjangJabatan::getJumlahPejabat($cc_selected, $x);
        }

        // GET TARGET COC
        $arr_target = TargetCoc::getTargetCluster($cc_selected, $tgl_akhir->format('m'), $tgl_akhir->format('Y'));

	    if (count($arr_target)==0) {
            return redirect('/')->with('warning', 'Target cluster CoC dari Kantor Pusat belum tersedia. Mohon tunggu sampai ada target CoC dari Kantor Pusat.');
        }

        // GET REALISASI
        $arr_realisasi = [];

        for($x=1;$x<=6;$x++) {
            $pejabat_list = JenjangJabatan::getListPejabat($cc_selected, $x);
            $realisasi = 0;

            foreach($pejabat_list as $pejabat){
                $realisasi += RealisasiCoc::getRealisasiJabatan($pejabat->getPositionDefinitive(), $pejabat->gsber, $tgl_awal, $tgl_akhir);
            }

            $arr_realisasi[$x] =  $realisasi;
        }

        return view('report.rekap_coc', compact('coCodeList', 'cc_selected', 'jenjang_list', 'arr_jml', 'arr_target', 'arr_realisasi', 'tgl_awal', 'tgl_akhir'));
    }

//    public function searchResultRekap(Request $request)
//    {
//        if($request->company_code==0)
//            return redirect('report/briefing-coc')->with('warning','Company Code belum dipilih');
//
//        $coCodeList[0] = 'Select Organisasi';
//        foreach (CompanyCode::all()->sortBy('id') as $wa) {
//            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
//        }
//        $company_code = CompanyCode::where('company_code', $request->company_code)->first();
//        $cc_selected = $company_code;
////        $realisasi_list = $company_code->realisasi;
//
//        return view('report.rekap_coc', compact('coCodeList', 'cc_selected'));
//    }

    public function searchResultRekap(Request $request)
    {
        $user = Auth::user();
        $tgl_awal = Date::parse($request->start_date);
        $tgl_akhir = Date::parse($request->end_date);

        $cc_selected = $request->company_code;
        $resultShifting = (new UnitKerjaUtil)->shiftingCompanyCode($user);

        if (empty($cc_selected)) {
            $cc_selected = $user->company_code;
        }

        $coCodeList = (new CompanyCodeUtil)->generateOptions($user, $resultShifting);

        $jenjang_list = JenjangJabatan::where('id','!=','7')->orderBy('id','asc')->get();

        // GET JML PEJABAT
        $arr_jml = [];

        for($x=1;$x<=6;$x++) {
            $arr_jml[$x] =  JenjangJabatan::getJumlahPejabat($cc_selected, $x);
        }

        // GET TARGET COC
        $arr_target = TargetCoc::getTargetCluster(
            $cc_selected,
            $tgl_akhir->format('m'),
            $tgl_akhir->format('Y')
        );

        if (count($arr_target)==0){
            return redirect('/')->with('warning', 'Target cluster CoC dari Kantor Pusat belum tersedia. Mohon tunggu sampai ada target CoC dari Kantor Pusat.');
        }

        // GET REALISASI
        $arr_realisasi = [];

        for ($x=1; $x<=6; $x++) {
            $pejabat_list = JenjangJabatan::getListPejabat($cc_selected, $x);
            $realisasi = 0;

            foreach($pejabat_list as $pejabat){
                $realisasi += RealisasiCoc::getRealisasiJabatan($pejabat->getPositionDefinitive(), $pejabat->gsber, $tgl_awal, $tgl_akhir);
            }

            $arr_realisasi[$x] =  $realisasi;
        }

        return view('report.rekap_coc', compact('coCodeList', 'cc_selected', 'jenjang_list', 'arr_jml', 'arr_target', 'arr_realisasi', 'tgl_awal', 'tgl_akhir'));
    }

    public function exportRekapCoc($company_code, $tgl_awal, $tgl_akhir)
    {
//        if($company_code==0)
//            return redirect('report/rekap-coc')->with('warning','Company Code belum dipilih');
//
//        $company_code = CompanyCode::where('company_code', $company_code)->first();
//        $cc_selected = $company_code;
//        $realisasi_list = $company_code->realisasi;
        Date::setLocale('id');
        $tgl_awal = Date::parse($tgl_awal);
        $tgl_akhir = Date::parse($tgl_akhir);

        $company_code = $company_code;

//        $cc_selected = CompanyCode::findOrFail(1);
        $cc_selected = CompanyCode::where('company_code', $company_code)->first();
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

        $jenjang_list = JenjangJabatan::where('id','!=','7')->orderBy('id','asc')->get();

//        $jml = JenjangJabatan::getJumlahPejabat('5500', $level, $jabatan_id);

//        JenjangJabatan::getJumlahPejabat('5500', 3);

        // GET JML PEJABAT
        $arr_jml = [];
        for($x=1;$x<=6;$x++) {
            $arr_jml[$x] =  JenjangJabatan::getJumlahPejabat($company_code, $x);
        }

        // GET TARGET COC
        $arr_target = TargetCoc::getTargetCluster($company_code, $tgl_akhir->format('m'), $tgl_akhir->format('Y'));

        if(count($arr_target)==0){
            return redirect('/')->with('warning', 'Target cluster CoC dari Kantor Pusat belum tersedia. Mohon tunggu sampai ada target CoC dari Kantor Pusat.');
        }

        // GET REALISASI
        $arr_realisasi = [];
        for($x=1;$x<=6;$x++) {
            $pejabat_list = JenjangJabatan::getListPejabat($company_code, $x);
            $realisasi = 0;
            foreach($pejabat_list as $pejabat){
                $realisasi += RealisasiCoc::getRealisasiJabatan($pejabat->getPositionDefinitive(), $pejabat->gsber, $tgl_awal, $tgl_akhir);
            }
//            $arr_realisasi[$x] =  RealisasiCoc::getRealisasiUnit($x, $company_code, $tgl_awal, $tgl_akhir);
            $arr_realisasi[$x] =  $realisasi;
        }

//        dd($arr_realisasi);

//        return view('report.rekap_coc', compact('coCodeList', 'cc_selected', 'jenjang_list', 'arr_jml', 'arr_target', 'arr_realisasi', 'tgl_awal', 'tgl_akhir'));


        Excel::create(date('YmdHis').'_rekap_coc',
            function ($excel) use ($cc_selected, $jenjang_list, $arr_jml, $arr_target, $arr_realisasi, $tgl_awal, $tgl_akhir) {

            $excel->sheet('Rekap CoC', function ($sheet) use ($cc_selected, $jenjang_list, $arr_jml, $arr_target, $arr_realisasi, $tgl_awal, $tgl_akhir) {
                $sheet->loadView('report/template_rekap_coc', [
                    'cc_selected'=>$cc_selected,
                    'jenjang_list'=>$jenjang_list,
                    'arr_jml'=>$arr_jml,
                    'arr_target'=>$arr_target,
                    'arr_realisasi'=>$arr_realisasi,
                    'tgl_awal'=>$tgl_awal,
                    'tgl_akhir'=>$tgl_akhir
                ]);
            });

        })->download('xlsx');
//        })->download('xls');
    }

    public function detilRekapCoC($company_code, $jenjang_id)
    {
        $tgl_awal = Date::parse(date('Y').'-01-01');
        $tgl_akhir = Date::now();

        if(Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root'))
            $company_code = $company_code;
        else
            $company_code = Auth::user()->company_code;

        $jenjang = JenjangJabatan::findOrFail($jenjang_id);

//        $cc_selected = CompanyCode::findOrFail(1);
        $cc_selected = CompanyCode::where('company_code', $company_code)->first();
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

//        $jenjang_list = JenjangJabatan::where('id','!=','7')->orderBy('id','asc')->get();

//        $jml = JenjangJabatan::getJumlahPejabat('5500', $level, $jabatan_id);

//        JenjangJabatan::getJumlahPejabat('5500', 3);

        // GET JML PEJABAT
//        $arr_jml = [];
//        for($x=1;$x<=6;$x++) {
            $pejabat_list =  JenjangJabatan::getListPejabat($company_code, $jenjang_id);
//        }

        // GET TARGET COC
        $target = TargetCoc::getTargetClusterJenjang($company_code, $tgl_akhir->format('m'), $tgl_akhir->format('Y'), $jenjang_id);

        // GET REALISASI
        $arr_realisasi = [];
        for($x=1;$x<=6;$x++) {
            $arr_realisasi[$x] =  RealisasiCoc::getRealisasiUnit($x, $company_code, $tgl_awal, $tgl_akhir);
        }

//        dd($arr_realisasi);

        return view('report.detil_rekap_coc', compact('coCodeList', 'cc_selected', 'pejabat_list', 'target', 'arr_realisasi', 'tgl_awal', 'tgl_akhir', 'jenjang'));
    }

    public function searchDetilRekapCoC(Request $request, $company_code, $jenjang_id)
    {
        $tgl_awal = Date::parse($request->start_date);
        $tgl_akhir = Date::parse($request->end_date);

        $company_code = $request->company_code;

        $jenjang = JenjangJabatan::findOrFail($jenjang_id);

//        $cc_selected = CompanyCode::findOrFail(1);
        $cc_selected = CompanyCode::where('company_code', $company_code)->first();
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

//        $jenjang_list = JenjangJabatan::where('id','!=','7')->orderBy('id','asc')->get();

//        $jml = JenjangJabatan::getJumlahPejabat('5500', $level, $jabatan_id);

//        JenjangJabatan::getJumlahPejabat('5500', 3);

        // GET JML PEJABAT
//        $arr_jml = [];
//        for($x=1;$x<=6;$x++) {
        $pejabat_list =  JenjangJabatan::getListPejabat($company_code, $jenjang_id);
//        }

        // GET TARGET COC
        $target = TargetCoc::getTargetClusterJenjang($company_code, $tgl_akhir->format('m'), $tgl_akhir->format('Y'), $jenjang_id);

        // GET REALISASI
        $arr_realisasi = [];
        for($x=1;$x<=6;$x++) {
            $arr_realisasi[$x] =  RealisasiCoc::getRealisasiUnit($x, $company_code, $tgl_awal, $tgl_akhir);
        }

//        dd($arr_realisasi);

        return view('report.detil_rekap_coc', compact('coCodeList', 'cc_selected', 'pejabat_list', 'target', 'arr_realisasi', 'tgl_awal', 'tgl_akhir', 'jenjang'));
    }

    public function exportDetilRekapCoC($company_code, $jenjang_id, $tgl_awal, $tgl_akhir)
    {
        Date::setLocale('id');
        $tgl_awal = Date::parse($tgl_awal);
        $tgl_akhir = Date::parse($tgl_akhir);

//        if(Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root'))
//            $company_code = $company_code;
//        else
            $company_code = $company_code;

        $jenjang = JenjangJabatan::findOrFail($jenjang_id);

//        $cc_selected = CompanyCode::findOrFail(1);
        $cc_selected = CompanyCode::where('company_code', $company_code)->first();
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

//        $jenjang_list = JenjangJabatan::where('id','!=','7')->orderBy('id','asc')->get();

//        $jml = JenjangJabatan::getJumlahPejabat('5500', $level, $jabatan_id);

//        JenjangJabatan::getJumlahPejabat('5500', 3);

        // GET JML PEJABAT
//        $arr_jml = [];
//        for($x=1;$x<=6;$x++) {
        $pejabat_list =  JenjangJabatan::getListPejabat($company_code, $jenjang_id);
//        }

        // GET TARGET COC
        $target = TargetCoc::getTargetClusterJenjang($company_code, $tgl_akhir->format('m'), $tgl_akhir->format('Y'), $jenjang_id);

        // GET REALISASI
        $arr_realisasi = [];
        for($x=1;$x<=6;$x++) {
            $arr_realisasi[$x] =  RealisasiCoc::getRealisasiUnit($x, $company_code, $tgl_awal, $tgl_akhir);
        }

//        dd($arr_realisasi);

//        return view('report.detil_rekap_coc', compact('coCodeList', 'cc_selected', 'pejabat_list', 'target', 'arr_realisasi', 'tgl_awal', 'tgl_akhir', 'jenjang'));

        Excel::create(date('YmdHis').'_detil_rekap_coc',
            function ($excel) use ($cc_selected, $pejabat_list, $target, $arr_realisasi, $tgl_awal, $tgl_akhir, $jenjang) {

                $excel->sheet('Rekap CoC', function ($sheet) use ($cc_selected, $pejabat_list, $target, $arr_realisasi, $tgl_awal, $tgl_akhir, $jenjang) {
                    $sheet->loadView('report/template_detil_rekap_coc', [
                        'cc_selected'=>$cc_selected,
                        'pejabat_list'=>$pejabat_list,
                        'target'=>$target,
                        'arr_realisasi'=>$arr_realisasi,
                        'tgl_awal'=>$tgl_awal,
                        'tgl_akhir'=>$tgl_akhir,
                        'jenjang'=>$jenjang
                    ]);
                });

            })->download('xlsx');
    }

    public function persentaseCoc()
    {
        ini_set('max_execution_time', 500);

        $jenis_coc_id = 1;
        $user = Auth::user();
        $tgl_awal = Date::parse(date('Y-m') . '-01');
        $tgl_akhir = Date::now();

        $cc_selected = request('company_code');
        $resultShifting = (new UnitKerjaUtil)->shiftingCompanyCode($user);

        if (empty($cc_selected)) {
            $cc_selected = $resultShifting;
        } else {
            $cc_selected = [$cc_selected];
        }

        $coCodeList = (new CompanyCodeUtil)->generateOptions($user, $resultShifting);

        $cocUtil = new CocUtil;
        $date = (object) [
            'start' => $tgl_awal,
            'end' => $tgl_akhir,
        ];

        $callback = $cocUtil->generateCallback($cc_selected, $date, 1);

        $role_admin_pusat = Role::with(['users' => $callback])->find(3);
        $role_admin_ki = Role::with(['users' => $callback])->find(6);
        $role_admin_unit = Role::with(['users' => $callback])->find(4);

        $user_admin_pusat = $role_admin_pusat->users;
        $user_admin_ki = $role_admin_ki->users;
        $user_admin_unit = $role_admin_unit->users;

        $users = $user_admin_unit->merge($user_admin_ki);
        $users = $cocUtil->generateReport($users->merge($user_admin_pusat));

        $startDate = $tgl_awal->format('d-m-Y');
        $endDate = $tgl_akhir->format('d-m-Y');

        $cc_selected = $cc_selected[0];

        return view('report.persentase_coc', compact(
            'coCodeList', 'cc_selected', 'tgl_awal', 'tgl_akhir', 'users', 'jenis_coc_id', 'user',
            'startDate', 'endDate'
        ));
    }

    public function searchPersentaseCoc(Request $request)
    {
        ini_set('max_execution_time', 500);

        $jenis_coc_id = $request->jenis_coc_id;
        $user = Auth::user();
        $tgl_awal = Date::parse($request->start_date);
        $tgl_akhir = Date::parse($request->end_date);

        $cc_selected = $request->company_code;

        $companyCode = (new UnitKerjaUtil)->shiftingCompanyCode($user);
        $coCodeList = (new CompanyCodeUtil())->generateOptions($user, $companyCode);

        $cocUtil = new CocUtil;
        $date = (object) [
            'start' => $tgl_awal,
            'end' => $tgl_akhir,
        ];

        if (empty($cc_selected)) {
            $cc_selected = $companyCode[0];
        } else {
            $cc_selected = [$cc_selected];
        }

        $callback = $cocUtil->generateCallback($cc_selected, $date, $jenis_coc_id);

        $role_admin_pusat = Role::with(['users' => $callback])->find(3);
        $role_admin_ki = Role::with(['users' => $callback])->find(6);
        $role_admin_unit = Role::with(['users' => $callback])->find(4);

        $user_admin_pusat = $role_admin_pusat->users;
        $user_admin_ki = $role_admin_ki->users;
        $user_admin_unit = $role_admin_unit->users;

        $users = $user_admin_unit->merge($user_admin_ki);
        $users = $cocUtil->generateReport($users->merge($user_admin_pusat));

        $startDate = $tgl_awal->format('d-m-Y');
        $endDate = $tgl_akhir->format('d-m-Y');

        $cc_selected = $cc_selected[0];

        return view('report.persentase_coc', compact(
            'coCodeList', 'cc_selected', 'tgl_awal', 'tgl_akhir', 'users', 'jenis_coc_id', 'user',
            'startDate', 'endDate'
        ));
    }

    public function exportPersentaseCoc($company_code, $tgl_awal, $tgl_akhir, $jenis_coc_id)
    {
        ini_set('max_execution_time', 500);
        $jenis_coc = JenisCoc::find($jenis_coc_id);
        $jenis_coc_string = strtoupper($jenis_coc->jenis);

        Date::setLocale('id');

        $tgl_awal = Date::parse($tgl_awal);
        $tgl_akhir = Date::parse($tgl_akhir);

//        if(Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root'))
//            $company_code = '5500';
//        else
        $company_code = $company_code;

        $cc_selected = CompanyCode::where('company_code', $company_code)->first();
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

        $role_admin_pusat = Role::find(3);
        $role_admin_ki = Role::find(6);
        $role_admin_unit = Role::find(4);

        $user_admin_pusat = $role_admin_pusat->users()->where('company_code', $company_code)->get();
        $user_admin_ki = $role_admin_ki->users()->where('company_code', $company_code)->get();
        $user_admin_unit = $role_admin_unit->users()->where('company_code', $company_code)->get();

        $users = $user_admin_unit->merge($user_admin_ki);
        $users = $users->merge($user_admin_pusat);

//        dd($users);

//        return view('report.persentase_coc', compact('coCodeList', 'cc_selected', 'tgl_awal', 'tgl_akhir', 'users'));

        Excel::create(date('YmdHis').'_persentase_coc',
            function ($excel) use ($cc_selected, $tgl_awal, $tgl_akhir, $users, $jenis_coc_string, $jenis_coc_id) {

                $excel->sheet('Persentase CoC', function ($sheet) use ($cc_selected, $tgl_awal, $tgl_akhir, $users, $jenis_coc_string, $jenis_coc_id) {
                    $sheet->loadView('report/template_persentase_coc', [
                        'jenis_coc_id'=>$jenis_coc_id,
                        'jenis_coc'=>$jenis_coc_string,
                        'cc_selected'=>$cc_selected,
                        'tgl_awal'=>$tgl_awal,
                        'tgl_akhir'=>$tgl_akhir,
                        'users'=>$users
                    ]);
                });

            })->download('xlsx');
    }

    public function _persentaseCoc()
    {
        ini_set('max_execution_time', 500);
        $jenis_coc_id = 1;
        $tgl_awal = Date::parse(date('Y-m').'-01');
        $tgl_akhir = Date::now();

        if(Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root'))
            $company_code = '8200';
        else
            $company_code = Auth::user()->company_code;

        $cc_selected = CompanyCode::where('company_code', $company_code)->first();
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

        return view('report.persentase_coc', compact('coCodeList', 'cc_selected', 'tgl_awal', 'tgl_akhir', 'jenis_coc_id'));
    }

    public function _searchPersentaseCoc(Request $request)
    {
        ini_set('max_execution_time', 500);
        $jenis_coc_id = $request->jenis_coc_id;
        $tgl_awal = Date::parse($request->start_date);
        $tgl_akhir = Date::parse($request->end_date);

        if (Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root')) {
            $company_code = '8200';
        } else {
            $company_code = Auth::user()->company_code;
        }

        $cc_selected = CompanyCode::where('company_code', $company_code)->first();
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

        $role_admin_pusat = Role::find(3);
        $role_admin_ki = Role::find(6);
        $role_admin_unit = Role::find(4);

        $user_admin_pusat = $role_admin_pusat->users()->where('company_code', $company_code)->get();
        $user_admin_ki = $role_admin_ki->users()->where('company_code', $company_code)->get();
        $user_admin_unit = $role_admin_unit->users()->where('company_code', $company_code)->get();

        $users = $user_admin_unit->merge($user_admin_ki);
        $users = $users->merge($user_admin_pusat);

        return view('report.persentase_coc', compact('coCodeList', 'cc_selected', 'tgl_awal', 'tgl_akhir', 'users', 'jenis_coc_id'));
    }

    public function _exportPersentaseCoc($company_code, $tgl_awal, $tgl_akhir, $jenis_coc_id)
    {
        ini_set('max_execution_time', 500);
        $jenis_coc = JenisCoc::find($jenis_coc_id);
        $jenis_coc_string = strtoupper($jenis_coc->jenis);

        Date::setLocale('id');

        $tgl_awal = Date::parse($tgl_awal);
        $tgl_akhir = Date::parse($tgl_akhir);

//        if(Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root'))
//            $company_code = '5500';
//        else
        $company_code = $company_code;

        $cc_selected = CompanyCode::where('company_code', $company_code)->first();
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

        $role_admin_pusat = Role::find(3);
        $role_admin_ki = Role::find(6);
        $role_admin_unit = Role::find(4);

        $user_admin_pusat = $role_admin_pusat->users()->where('company_code', $company_code)->get();
        $user_admin_ki = $role_admin_ki->users()->where('company_code', $company_code)->get();
        $user_admin_unit = $role_admin_unit->users()->where('company_code', $company_code)->get();

        $users = $user_admin_unit->merge($user_admin_ki);
        $users = $users->merge($user_admin_pusat);

//        dd($users);

//        return view('report.persentase_coc', compact('coCodeList', 'cc_selected', 'tgl_awal', 'tgl_akhir', 'users'));

        Excel::create(date('YmdHis').'_persentase_coc',
            function ($excel) use ($cc_selected, $tgl_awal, $tgl_akhir, $users, $jenis_coc_string, $jenis_coc_id) {

                $excel->sheet('Persentase CoC', function ($sheet) use ($cc_selected, $tgl_awal, $tgl_akhir, $users, $jenis_coc_string, $jenis_coc_id) {
                    $sheet->loadView('report/template_persentase_coc', [
                        'jenis_coc_id'=>$jenis_coc_id,
                        'jenis_coc'=>$jenis_coc_string,
                        'cc_selected'=>$cc_selected,
                        'tgl_awal'=>$tgl_awal,
                        'tgl_akhir'=>$tgl_akhir,
                        'users'=>$users
                    ]);
                });

            })->download('xlsx');
    }

    public function statusCoc()
    {
        $user = Auth::user();
        $tgl = Carbon::now()->format('d-m-Y');
        $tgl_awal = Date::parse(request('start_date', $tgl));
        $tgl_akhir = Date::parse(request('end_date', $tgl));

        $status_coc = request('status_coc', 'OPEN');

        $bsAreaList = (new BusinessAreaUtil)->generateOptions(
            $user,
            (new UnitKerjaUtil)->shiftingBusinessArea($user),
            'Business Area'
        );
        $ba_selected = request('business_area');

        if (empty($ba_selected)) {
            $ba_selected = (new UnitKerjaUtil)->shiftingBusinessArea($user);
        } else {
            $ba_selected = [$ba_selected];
        }

        // get id coc unit
        $coc_unit = Coc::where('scope', 'local')
            ->whereIn('business_area', $ba_selected)
            ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
            ->where('status',$status_coc)
            ->get(['id'])
            ->toArray();

        $coc_list = Coc::whereIn('id', $coc_unit)->get();

        $ba_selected = $ba_selected[0];

        return view('report.status_coc', compact(
            'ba_selected', 'bsAreaList', 'tgl_awal', 'tgl_akhir', 'coc_list', 'status_coc',
            'user'
        ));
    }

    public function statusCocCompanyCode()
    {
        $user = Auth::user();
        $now = Carbon::now()->format('d-m-Y');
        $tgl_awal = Date::parse(request('start_date', $now));
        $tgl_akhir = Date::parse(request('end_date', $now));
        $status_coc = request('status_coc', 'OPEN');

        $cc_selected = request('company_code');
        $resultShifting = (new UnitKerjaUtil())->shiftingCompanyCode($user, false);

        if (empty($cc_selected)) {
            $cc_selected = $resultShifting;
        } else {
            $cc_selected = [$cc_selected];
        }

        $ccList = (new CompanyCodeUtil())->generateOptions($user, $resultShifting);

        // get id coc unit
        $coc_unit = Coc::where('scope', 'local')
            ->whereIn('company_code', $cc_selected)
            ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
            ->where('status',$status_coc)
            ->get(['id'])
            ->toArray();

        $coc_list = Coc::whereIn('id', $coc_unit)->get();
        $cc_selected = $cc_selected[0];

        return view('report.status_coc_cc', compact(
            'cc_selected', 'ccList', 'tgl_awal', 'tgl_akhir', 'coc_list', 'status_coc',
            'user'
        ));
    }

    public function searchStatusCoc(Request $request)
    {
        $tgl_awal = Date::parse($request->start_date);
        $tgl_akhir = Date::parse($request->end_date);

        if ($request->business_area != null)
            $ba_selected = $request->business_area;
        else
            $ba_selected = Auth::user()->business_area;

        $bsAreaList[0] = 'Select Business Area';
        if(Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root')) {
            foreach (BusinessArea::all()->sortBy('id') as $wa) {
                $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
            }
        }
        else{
            foreach (BusinessArea::where('company_code', Auth::user()->company_code)->orderBy('id','asc')->get() as $wa) {
                $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
            }
        }

        $arr_org_user = Auth::user()->getArrOrgLevel();

        /*// get id coc local dalam satu organisasi
        $coc_local = Coc::where('scope', 'local')
            ->whereIn('orgeh', $arr_org_user)
            ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
            ->where('status',$request->status_coc)
            ->get(['id'])
            ->toArray();*/

        /*$coc_list = Coc::whereIn('id', $coc_local)
            ->orWhere(function ($query) use ($coc_unit) {
                $query->whereIn('id', $coc_unit);
            })
            ->get();*/

        $status_coc = $request->status_coc;


        return view('report.status_coc', compact('ba_selected', 'bsAreaList', 'tgl_awal', 'tgl_akhir', 'status_coc'));
    }

    public function searchStatusCocCompanyCode (Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now()->format('d-m-Y');
        $tgl_awal = Date::parse(request('start_date', $now));
        $tgl_akhir = Date::parse(request('end_date', $now));

        $resultShifting = (new UnitKerjaUtil)->shiftingCompanyCode($user, false);
        $ccList = (new CompanyCodeUtil())->generateOptions($user, $resultShifting);
        $cc_selected = request('company_code');

        $status_coc = $request->status_coc;

        return view('report.status_coc_cc', compact(
            'cc_selected', 'ccList', 'tgl_awal', 'tgl_akhir', 'status_coc',
            'user'
        ));
    }

    public function exportStatusCoc($business_area, $tgl_awal, $tgl_akhir, $status_coc){

        Date::setLocale('id');

        $tgl_awal = Date::parse($tgl_awal);
        $tgl_akhir = Date::parse($tgl_akhir);

        /*if ($request->business_area != null)
            $ba_selected = $request->business_area;
        else
            $ba_selected = Auth::user()->business_area;

        $bsAreaList[0] = 'Select Business Area';
        foreach (BusinessArea::all()->sortBy('id') as $wa) {
            $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
        }*/

//        $arr_org_user = Auth::user()->getArrOrgLevel();

        /*// get id coc local dalam satu organisasi
        $coc_local = Coc::where('scope', 'local')
            ->whereIn('orgeh', $arr_org_user)
            ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
            ->where('status',$status_coc)
            ->get(['id'])
            ->toArray();*/

        // get id coc unit
        $coc_unit = Coc::where('scope', 'local')
            ->where('business_area', $business_area)
            ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
            ->where('status',$status_coc)
            ->get(['id'])
            ->toArray();

        /*$coc_list = Coc::whereIn('id', $coc_local)
            ->orWhere(function ($query) use ($coc_unit) {
                $query->whereIn('id', $coc_unit);
            })
            ->get();*/

        $coc_list = Coc::whereIn('id', $coc_unit)->get();

//        $status_coc = $request->status_coc;
        $business_area = BusinessArea::where('business_area', $business_area)->first();

        Excel::create(date('YmdHis').'_status_coc', function ($excel) use ($business_area, $coc_list, $status_coc) {

            $excel->sheet('Status CoC', function ($sheet) use ($business_area, $coc_list, $status_coc) {
                $sheet->loadView('report/template_status_coc', ['business_area'=>$business_area, 'coc_list'=>$coc_list, 'status_coc'=>$status_coc]);
            });

        })->download('xlsx');
    }

    public function exportStatusCocCompanyCode($company_code, $tgl_awal, $tgl_akhir, $status_coc){

        Date::setLocale('id');

        $tgl_awal = Date::parse($tgl_awal);
        $tgl_akhir = Date::parse($tgl_akhir);


//        $arr_org_user = Auth::user()->getArrOrgLevel();

        /*// get id coc local dalam satu organisasi
        $coc_local = Coc::where('scope', 'local')
            ->whereIn('orgeh', $arr_org_user)
            ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
            ->where('status',$status_coc)
            ->get(['id'])
            ->toArray();*/

        // get id coc unit
        $coc_unit = Coc::where('scope', 'local')
            ->where('company_code', $company_code)
            ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
            ->where('status',$status_coc)
            ->get(['id'])
            ->toArray();

        /*$coc_list = Coc::whereIn('id', $coc_local)
            ->orWhere(function ($query) use ($coc_unit) {
                $query->whereIn('id', $coc_unit);
            })
            ->get();*/

        $coc_list = Coc::whereIn('id', $coc_unit)->get();

//        $status_coc = $request->status_coc;
        $company_code = CompanyCode::where('company_code', $company_code)->first();

        Excel::create(date('YmdHis').'_status_coc', function ($excel) use ($company_code, $coc_list, $status_coc) {

            $excel->sheet('Status CoC', function ($sheet) use ($company_code, $coc_list, $status_coc) {
                $sheet->loadView('report/template_status_coc_cc', ['company_code'=>$company_code, 'coc_list'=>$coc_list, 'status_coc'=>$status_coc]);
            });

        })->download('xlsx');
    }

    public function jumlahCoc(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $tgl_awal = Date::parse(
            ! empty(request('start_date'))
                ? request('start_date')
                : $now
        );
        $tgl_akhir = Date::parse(
            ! empty(request('end_date'))
                ? request('end_date')
                : $now
        );
        $cc_selected = request('company_code');
        $resultShifting = (new UnitKerjaUtil)->shiftingCompanyCode($user);

        if (empty($cc_selected)) {
            $cc_selected = $resultShifting;
        } else {
            $cc_selected = [$cc_selected];
        }

        $coCodeList = (new CompanyCodeUtil)->generateOptions($user, $resultShifting);

        $query = BusinessArea::whereIn('company_code', $cc_selected)
            ->orderBy('business_area', request()->input('order.0.dir'));

        $datatable = Datatable::make($query)
            ->rowView('report.jumlah_coc_row', compact('tgl_awal', 'tgl_akhir'))
            ->search(function ($builder, $keyword) {
                $keyword = strtolower($keyword);
                $builder->where(function($q) use ($keyword) {
                    $q->where(DB::raw('lower(business_area)'), 'like', "%$keyword%")
                        ->orWhere(DB::raw('lower(description)'), 'like', "%$keyword%");
                });
            })
            ->columns([
                ['data' => 'unit', 'sortable' => true],
                ['data' => 'cancel', 'sortable' => false],
                ['data' => 'open', 'sortable' => false],
                ['data' => 'complete', 'sortable' => false],
            ]);

        if (request()->wantsJson()) {
            return $datatable->toJson();
        }

        $data = $query->get();
        $total_coc_cancel = 0;
        $total_coc_open = 0;
        $total_coc_comp = 0;

        foreach ($data as $item) {
            $total_coc_cancel += $item->getSumCocRangeDate($tgl_awal, $tgl_akhir, 'CANC');
            $total_coc_open += $item->getSumCocRangeDate($tgl_awal, $tgl_akhir, 'OPEN');
            $total_coc_comp += $item->getSumCocRangeDate($tgl_awal, $tgl_akhir, 'COMP');
        }

        $cc_selected = $cc_selected[0];

        return view('report.jumlah_coc', compact(
            'coCodeList', 'cc_selected', 'tgl_awal', 'tgl_akhir', 'datatable', 'total_coc_cancel', 'total_coc_comp', 'total_coc_open',
            'user'
        ));
    }

    public function exportJumlahCoc($company_code, $tgl_awal, $tgl_akhir)
    {
        Date::setLocale('id');

        $tgl_awal = Date::parse($tgl_awal);
        $tgl_akhir = Date::parse($tgl_akhir);

//        if(Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('root'))
//            $company_code = '5500';
//        else
        $company_code = $company_code;

        $cc_selected = CompanyCode::where('company_code', $company_code)->first();
//        $coCodeList[0] = 'Select Company Code';
//        foreach (CompanyCode::all()->sortBy('id') as $wa) {
//            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
//        }

//        $role = Role::where('name', 'admin_unit')->first();

//        $users = $role->users()->where('company_code', $company_code)->get();

//        dd($users);

//        return view('report.persentase_coc', compact('coCodeList', 'cc_selected', 'tgl_awal', 'tgl_akhir', 'users'));

        Excel::create(date('YmdHis').'_jumlah_coc',
            function ($excel) use ($cc_selected, $tgl_awal, $tgl_akhir) {

                $excel->sheet('Jumlah CoC', function ($sheet) use ($cc_selected, $tgl_awal, $tgl_akhir) {
                    $sheet->loadView('report/template_jumlah_coc', [
                        'cc_selected'=>$cc_selected,
                        'tgl_awal'=>$tgl_awal,
                        'tgl_akhir'=>$tgl_akhir
                    ]);
                });

            })->download('xlsx');
    }

    public function monitoringCheckInCoc(){

        $bulan_list = [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $selected_bulan = date('m');
        if (request('bulan')) {
            $selected_bulan = request('bulan');
        }

        $tahun_list = [];
        for($x=date('Y');$x>=2017;$x--) {
            $tahun_list[$x] = $x;
        }

        $selected_tahun = date('Y');
        if (request('tahun')) {
            $selected_tahun = request('tahun');
        }

        $selected_week = 2;

        // get organisasi
        // test UID Jateng
        // $unit = UnitMonitoring::where('orgeh','15300000')->first();
        // Test DIVSTI
        // $unit = UnitMonitoring::where('orgeh','10073855')->first();
        // dd($unit->getPersentaseCocWeeks($selected_bulan, $selected_tahun));

        // get start date and end date weeks
        $arr_date_week = [];
        for($selected_week = 1; $selected_week <= 5; $selected_week++){
            $startOfWeek = Carbon::create($selected_tahun, $selected_bulan, 1)->startOfWeek(Carbon::MONDAY);
            $startOfWeek->addWeeks($selected_week - 1);
        
            $endOfWeek = clone $startOfWeek;
            $endOfWeek->endOfWeek(Carbon::SUNDAY)->subDays(2);

            $arr_date_week[$selected_week] = [
                'start' => $startOfWeek->format('d/m/Y'),
                'end' => $endOfWeek->format('d/m/Y')
            ];
        }

        // view monitoring check in coc
        return view('report/monitoring_checkin_coc', compact('bulan_list', 'tahun_list', 'selected_bulan', 'selected_tahun', 'arr_date_week'));
    }

    public function persentaseBacaMateri(){
        set_time_limit(300);

        $bulan_list = [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $selected_bulan = date('m');
        if (request('bulan')) {
            $selected_bulan = request('bulan');
        }

        $tahun_list = [];
        for($x=date('Y');$x>=2017;$x--) {
            $tahun_list[$x] = $x;
        }

        $selected_tahun = date('Y');
        if (request('tahun')) {
            $selected_tahun = request('tahun');
        }

        function getChildOrgeh($orgeh,$org){
            $arr_org_in = [];
            $arr_org_in[] = $orgeh;
            if(array_key_exists($orgeh,$org)){
                foreach($org[$orgeh] as $o){
                    $arr_org_in[]=$o;
                    $arr_org_in = array_merge($arr_org_in, getChildOrgeh($o,$org));
                }
            }else{
                $arr_org_in[] = $orgeh;
            }
            // remove duplicate
            $arr_org_in = array_unique($arr_org_in);

            return $arr_org_in;
        }

        $unit = UnitMonitoring::where('status', 'ACTV')
            ->orderBy('orgeh', 'asc')->get();
        $arr_org = [];
        $arr_org_by_child = [];
        $arr_org_clean = [];
        $organisasi = StrukturOrganisasi::where('status','ACTV')->get();
        //map data organisasi ke array berdasarkan parent
        foreach($organisasi as $org){
            if(!array_key_exists($org->sobid,$arr_org)){
                $arr_org[$org->sobid] = [];
            }
            array_push($arr_org[$org->sobid],$org->objid);
        }
        foreach($unit as $un){
                $arr_org_clean[$un->orgeh]['objid'] = [];
                $arr_org_clean[$un->orgeh]['count_pegawai'] = 0;
                $arr_org_clean[$un->orgeh]['count_week'][1] = 0;
                $arr_org_clean[$un->orgeh]['count_week'][2] = 0;
                $arr_org_clean[$un->orgeh]['count_week'][3] = 0;
                $arr_org_clean[$un->orgeh]['count_week'][4] = 0;
                $arr_org_clean[$un->orgeh]['count_week'][5] = 0;
                $arr_org_clean[$un->orgeh]['pegawai_week'][1] = [];
                $arr_org_clean[$un->orgeh]['pegawai_week'][2] = [];
                $arr_org_clean[$un->orgeh]['pegawai_week'][3] = [];
                $arr_org_clean[$un->orgeh]['pegawai_week'][4] = [];
                $arr_org_clean[$un->orgeh]['pegawai_week'][5] = [];
                $arr_org_clean[$un->orgeh]['nama_unit'] = $un->nama_unit;
                // // get child unit
                $arr_org_clean[$un->orgeh]['objid'] = array_merge($arr_org_clean[$un->orgeh]['objid'], getChildOrgeh($un->orgeh,$arr_org));
        }
        foreach ($arr_org_clean as $key => $value) {
            foreach ($value['objid'] as $objid) {
                $arr_org_by_child[$objid] = $key;
            }
        }

        //get all user
        $user = User::where('status','ACTV')->select('orgeh','nip')->get()->toArray();
        $user_org_arr = array();
        foreach($user as $us){
            if(($us['orgeh']!=0 || $us['orgeh']!='0') && array_key_exists($us['orgeh'], $arr_org_by_child)){
                $org_parent = $arr_org_by_child[$us['orgeh']];
                $arr_org_clean[$org_parent]['count_pegawai'] += 1;
                $user_org_arr[$us['nip']] = $us['orgeh'];
            }
        }
        
        // get start date and end date weeks
        $arr_date_week = [];
        $materi_week = [];
        for($selected_week = 1; $selected_week <= 5; $selected_week++){
            $startOfWeek = Carbon::create($selected_tahun, $selected_bulan, 1)->startOfWeek(Carbon::MONDAY);
            $startOfWeek->addWeeks($selected_week - 1);
        
            $endOfWeek = clone $startOfWeek;
            $endOfWeek->endOfWeek(Carbon::SUNDAY)->subDays(2);

            $arr_date_week[$selected_week] = [
                'start' => $startOfWeek->format('d/m/Y'),
                'end' => $endOfWeek->format('d/m/Y')
            ];
            
            $materi = Materi::where('jenis_materi_id', '1')
                        ->whereDate('tanggal', '>=', $startOfWeek)
                        ->whereDate('tanggal', '<=', $endOfWeek)
                        ->first();
            if($materi){
                $materi_week[$selected_week]['id'] = $materi->id;
                $materi_week[$selected_week]['peserta'] = [];
                foreach($materi->reader as $reader){
                    //skip jika pegawai sudah status INAC
                    if(array_key_exists($reader->nip, $user_org_arr)){
                        $top_org = $arr_org_by_child[$user_org_arr[$reader->nip]];
                        if(!in_array($reader->nip,$arr_org_clean[$top_org]['pegawai_week'][$selected_week])){
                            $arr_org_clean[$top_org]['count_week'][$selected_week] += 1;
                            $arr_org_clean[$top_org]['pegawai_week'][$selected_week][] = $reader->nip;
                        }
                    }
                }
            }
        }
        $tes = array_unique($arr_org_clean['10086275']['pegawai_week'][1]);
        return view('report/persentase_baca_materi', compact('bulan_list', 'tahun_list', 'selected_bulan', 'selected_tahun', 'arr_date_week','arr_org_clean'));
    }

    public function exportPersentaseBacaMateri(){

        $bulan_list = [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $selected_bulan = request('bulan');
        $selected_tahun = request('tahun');

        // get start date and end date weeks
        $arr_date_week = [];
        for($selected_week = 1; $selected_week <= 5; $selected_week++){
            $startOfWeek = Carbon::create($selected_tahun, $selected_bulan, 1)->startOfWeek(Carbon::MONDAY);
            $startOfWeek->addWeeks($selected_week - 1);
        
            $endOfWeek = clone $startOfWeek;
            $endOfWeek->endOfWeek(Carbon::SUNDAY)->subDays(2);

            $arr_date_week[$selected_week] = [
                'start' => $startOfWeek->format('d/m/Y'),
                'end' => $endOfWeek->format('d/m/Y')
            ];
        }

        // get Unit Monitoring
        $unit_monitoring = UnitMonitoring::where('status', 'ACTV')->get();

        Excel::create(date('YmdHis').'_persentase_baca_materi_'.$selected_bulan.'_'.$selected_tahun,
            function ($excel) use ($bulan_list, $selected_bulan, $selected_tahun, $arr_date_week, $unit_monitoring) {

                $excel->sheet('Persentase Baca Materi', function ($sheet) use ($bulan_list, $selected_bulan, $selected_tahun, $arr_date_week, $unit_monitoring) {
                    $sheet->loadView('report/template_persentase_baca_materi', [
                        'bulan_list'=>$bulan_list,
                        'selected_bulan'=>$selected_bulan,
                        'selected_tahun'=>$selected_tahun,
                        'arr_date_week'=>$arr_date_week,
                        'unit_monitoring'=>$unit_monitoring
                    ]);
                });

            })->download('xlsx');

        // view monitoring check in coc
        // return view('report/persentase_baca_materi', compact('bulan_list', 'tahun_list', 'selected_bulan', 'selected_tahun', 'arr_date_week'));
    }

    public function monitoringBacaMateriPegawai(){

        $bulan_list = [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $selected_bulan = date('m');
        if (request('bulan')) {
            $selected_bulan = request('bulan');
        }

        $tahun_list = [];
        for($x=date('Y');$x>=2017;$x--) {
            $tahun_list[$x] = $x;
        }

        $selected_tahun = date('Y');
        if (request('tahun')) {
            $selected_tahun = request('tahun');
        }

        $selected_minggu = 1;
        if (request('minggu_ke')) {
            $selected_minggu = request('minggu_ke');
        }

        $selected_unit = '10073751';
        if (request('orgeh')) {
            $selected_unit = request('orgeh');
        }

        // get range tanggal
        $startOfWeek = Carbon::create($selected_tahun, $selected_bulan, 1)->startOfWeek(Carbon::MONDAY);
        $startOfWeek->addWeeks($selected_minggu - 1);
    
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->endOfWeek(Carbon::SUNDAY)->subDays(2);

        // get id materi where tanggal between startOfWeek and endOfWeek
        $materi = Materi::where('jenis_materi_id', '1')
                    ->whereDate('tanggal', '>=', $startOfWeek)
                    ->whereDate('tanggal', '<=', $endOfWeek)
                    ->first();
                
        // get all unit monitoring
        $unit_monitoring = UnitMonitoring::where('status', 'ACTV')->get();
        $unit_list = [];
        foreach($unit_monitoring as $unit){
            $unit_list[$unit->orgeh] = $unit->nama_unit;
        }

        // view monitoring check in coc
        return view('report/monitoring_baca_materi_pegawai', compact('bulan_list', 'tahun_list', 'unit_list', 'selected_bulan', 'selected_tahun', 'selected_minggu', 'selected_unit', 'startOfWeek', 'endOfWeek', 'materi'));
    }

    public function exportPesertaBacaMateri(Request $request){
        ini_set('max_execution_time', 500);
        
        $selected_bulan = $request->bulan;
        $selected_tahun = $request->tahun;
        $selected_minggu = $request->minggu_ke;
        $selected_unit = $request->orgeh;

        // get range tanggal
        $startOfWeek = Carbon::create($selected_tahun, $selected_bulan, 1)->startOfWeek(Carbon::MONDAY);
        $startOfWeek->addWeeks($selected_minggu - 1);
    
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->endOfWeek(Carbon::SUNDAY)->subDays(2);

        // get id materi where tanggal between startOfWeek and endOfWeek
        $materi = Materi::where('jenis_materi_id', '1')
                    ->whereDate('tanggal', '>=', $startOfWeek)
                    ->whereDate('tanggal', '<=', $endOfWeek)
                    ->first();

        // cari organisasi2 di bawah orgeh coc
        $organisasi = StrukturOrganisasi::where('objid', $selected_unit)->first();

        // get arr orgeh
        $arr_org = [$organisasi->objid];
        // get child unit
        $arr_org = array_merge($arr_org, $organisasi->getArrChildOrgeh($organisasi->objid));

        // get id reader materi
        //$arr_reader = $materi->reader()->pluck('user_id')->unique()->toArray();

        // cari semua pegawai di organisasi 
        $arr_pegawai = [];
        $arr_pegawai_baca = [];
        $arr_pegawai_aktif = [];
        $list_pegawai = User::where('status', 'ACTV')
            ->whereIn('orgeh', $arr_org)
            ->get();
        foreach ($list_pegawai as $peg) {
            $arr_pegawai[$peg->id] = $peg;
            $arr_pegawai_aktif[] = $peg->id;
        }

        $arr_user_id = $list_pegawai->pluck('id')->toArray();

        $chunks = array_chunk($arr_user_id, 1000);

        $arr_reader = [];
        // foreach($chunks as $chunk){
        //     $reader_list = $materi->reader()->with(['user'])
        //                     ->whereIn('nip', $chunk)
        //                     ->get();
        //
        //     // remove duplicate user
        //     // $reader_list = $reader_list->unique('user_id');
        //
        //     $arr_reader = array_merge($arr_reader, $reader_list->pluck('user_id')->toArray());
        // }
        // dd($arr_reader);

        // get ReadMateri where user_id in list_pegawai
        // $reader_list = $materi->reader()->with(['user'])
        //                     ->whereIn('user_id', $arr_reader)
        //                     ->get();
        $reader_list = collect();
        // Chunk the array of reader IDs
        // $chunks = array_chunk($arr_reader, 800);

        foreach ($chunks as $chunk) {
            // Query the database for each chunk of reader IDs
            $chunkReaderList = $materi->reader()
                // ->with(['user'])
                ->whereIn('user_id', $chunk)
                ->get();
            
            // Concatenate the chunk results into the $reader_list collection
            $reader_list = $reader_list->merge($chunkReaderList);
            $arr_reader = array_merge($arr_reader, $reader_list->pluck('user_id')->toArray());
        }
        // remove duplicate user
        foreach ($arr_reader as $read) {
            $arr_pegawai_baca[] = $read;
        }
        $reader_list = $reader_list->unique('user_id');
        
        // cari pegawai yang sudah baca materi
        // $reader_list = User::where('status', 'ACTV')
        //     ->whereIn('orgeh', $arr_org)
        //     ->whereIn('id', $arr_reader)
        //     ->get();
        
        $arr_reader = $reader_list->pluck('user_id')->toArray();

        // cari pegawai yang belum baca materi
        // $absence_list = User::where('status', 'ACTV')
        //     ->whereIn('orgeh', $arr_org)
        //     ->whereNotIn('id', $arr_reader)
        //     ->get();
        // dd($reader_list);
        // dd($arr_pegawai[7676]);

        Excel::create(date('YmdHis').'_peserta_baca_materi_'.$materi->id,
            function ($excel) use ($materi, $reader_list, $arr_pegawai, $arr_reader, $arr_pegawai_aktif) {
                $excel->sheet('Sudah Baca Materi', function ($sheet) use ($materi, $reader_list, $arr_pegawai) {
                    $sheet->loadView('report/template_baca_materi', [
                        'materi'=>$materi,
                        'reader_list'=>$reader_list,
                        'arr_pegawai' => $arr_pegawai
                    ]);
                });

                $excel->sheet('Belum Baca Materi', function ($sheet) use ($materi, $arr_pegawai, $arr_pegawai_aktif, $arr_reader) {
                    $arr_pegawai_aktif = array_map('strval',$arr_pegawai_aktif);
                    $arr_reader = array_map('strval',$arr_reader);
                    $absence_list = array_values(array_diff($arr_pegawai_aktif, $arr_reader));
                    $absence_list = array_map('intval', $absence_list);
                    $sheet->loadView('report/template_belum_baca_materi', [
                        'materi'=>$materi,
                        'absence_list'=>$absence_list,
                        'arr_pegawai' => $arr_pegawai,
                    ]);
                });

            })->download('xlsx');

    }
}
