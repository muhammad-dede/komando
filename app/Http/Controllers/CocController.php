<?php

namespace App\Http\Controllers;

use App\Activity;
use App\API;
use App\Attachment;
use App\AttachmentMateri;
use App\Attendant;
use App\Autocomplete;
use App\Bot;
use App\BusinessArea;
use App\Coc;
use App\Comment;
use App\CompanyCode;
use App\DoDont;
use App\Event;
use App\GalleryCoc;
use App\Http\Requests\CompleteRequest;
use App\Http\Requests\JadwalKIRequest;
use App\Http\Requests\JadwalLocalRequest;
use App\Http\Requests\JadwalRequest;
use App\Http\Requests\MateriRequest;
use App\JenisCoc;
use App\JenjangJabatan;
use App\MailLog;
use App\Materi;
use App\Notification;
use App\PA0001;
use App\PedomanPerilaku;
use App\Pelanggaran;
use App\PelanggaranCoc;
use App\Perilaku;
use App\PerilakuDoDont;
use App\RealisasiCoc;
use App\RitualCoc;
use App\Role;
use App\StatusCheckIn;
use App\StrukturJabatan;
use App\StrukturOrganisasi;
use App\Tema;
use App\TemaCoc;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\IsuNasional;
use App\IsuNasionalCoC;
use App\PA0032;
use App\Services\Datatable;
use App\UnitKerja;
use App\Utils\BusinessAreaUtil;
use App\Utils\UnitKerjaUtil;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Jenssegers\Date\Date;
use Maatwebsite\Excel\Facades\Excel;
use MaddHatter\LaravelFullcalendar\Calendar;

class CocController extends Controller
{
    public function index()
    {
        // Activity::log('Akses Calendar CoC', 'success');

        $events = [];

        // ambil tema
        $tema_coc_list = TemaCoc::whereYear('start_date', '=', date('Y'))->get();
        foreach ($tema_coc_list as $tema) {
            $event_tema = $tema->event;
            $event = Calendar::event(
                'TEMA : ' . $event_tema->title,
                1,
                $event_tema->start->format('Y-m-d'),
                $event_tema->end->addDays(1)->format('Y-m-d'),
                $event_tema->id,
                [
                    'url' => (Auth::user()->can('input_coc_local')) ? url('coc/create/local/' . $tema->tema->id) : '',
                    'className' => $event_tema->class_name
                ]
            );
            $events[] = $event;
        }

        $arr_org_user = Auth::user()->getArrOrgLevel();

        // dd($arr_org_user);

        // ambil coc hari ini

        // get id coc local dalam satu organisasi
        $coc_local = Coc::where('scope', 'local')
            ->whereIn('orgeh', $arr_org_user)
            ->whereDate('tanggal_jam', '=', date('Y-m-d'))
            ->where('status', '!=', 'CANC')
            ->get(['id'])
            ->toArray();

        // get id coc unit
        $coc_unit = Coc::where('scope', 'unit')
            ->where('company_code', Auth::user()->company_code)
            ->whereDate('tanggal_jam', '=', date('Y-m-d'))
            ->where('status', '!=', 'CANC')
            ->get(['id'])
            ->toArray();

        $coc_today = Coc::whereIn('id', $coc_local)
            ->orWhere(function ($query) use ($coc_unit) {
                $query->whereIn('id', $coc_unit);
            })
            ->get();

        // get all date coc

        // ambil coc local
        $coc_list = Coc::where('scope', 'local')
            ->whereIn('orgeh', $arr_org_user)
            ->where('status', '!=', 'CANC')
            ->whereYear('tanggal_jam', '=', date('Y'))
            ->get();
        // dd($coc_list);
        foreach ($coc_list as $coc) {
            $event_coc = $coc->event;
            $event = Calendar::event(
                $event_coc->title,
                0,
                $event_coc->start,
                $event_coc->start,
                $event_coc->id,
                [
                    'url' => ($coc->checkAtendant(Auth::user()->id) || $coc->status == 'COMP') ? url('coc/event/' . $coc->id) : url('coc/check-in/' . $coc->id),
                    'className' => $event_coc->class_name
                ]
            );
            $events[] = $event;
        }

        // ambil coc unit
        $coc_list = Coc::where('scope', 'unit')
            ->where('company_code', Auth::user()->company_code)
            ->where('status', '!=', 'CANC')
            ->whereYear('tanggal_jam', '=', date('Y'))
            ->get();
        foreach ($coc_list as $coc) {
            $event_coc = $coc->event;
            $event = Calendar::event(
                $event_coc->title,
                1,
                $event_coc->start,
                $event_coc->start,
                $event_coc->id,
                [
                    'url' => ($coc->checkAtendant(Auth::user()->id) || $coc->status == 'COMP') ? url('coc/event/' . $coc->id) : url('coc/check-in/' . $coc->id),
                    'className' => $event_coc->class_name
                ]
            );
            $events[] = $event;
        }

        // ambil materi
        if (Auth::user()->can('input_coc_local') || Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('admin_ki') || Auth::user()->hasRole('admin_unit')) {
            // ambil materi pusat
            $materi_list = Materi::where('jenis_materi_id', '1')->whereYear('tanggal', '=', date('Y'))->get();
            foreach ($materi_list as $materi) {
                $event_materi = $materi->event;
                $event = Calendar::event(
                    'M.PST : ' . $event_materi->title,
                    1,
                    $event_materi->start,
                    $event_materi->start,
                    $event_materi->id,
                    [
                        'url' => (Auth::user()->can('input_coc_local')) ? url('coc/initial/' . $materi->id) : '',
                        'className' => $event_materi->class_name
                    ]
                );
                $events[] = $event;
            }

            // ambil materi GM
            $materi_list = Materi::where('jenis_materi_id', '2')
                ->where('company_code', Auth::user()->company_code)
                ->whereYear('tanggal', '=', date('Y'))
                ->get();
            foreach ($materi_list as $materi) {
                $event_materi = $materi->event;
                $event = Calendar::event(
                    'M.GM : ' . $event_materi->title,
                    1,
                    $event_materi->start,
                    $event_materi->start,
                    $event_materi->id,
                    [
                        'url' => (Auth::user()->can('input_coc_local')) ? url('coc/initial/' . $materi->id) : '',
                        'className' => $event_materi->class_name
                    ]
                );
                $events[] = $event;
            }

        }

        $calendar = \Calendar::addEvents($events)
            ->setOptions([
                'firstDay' => 1
            ])->setCallbacks([

            ]);
//        $tema_list[] = 'Select Tema';
        foreach (Tema::all()->sortBy('id') as $wa) {
            $tema_list[$wa->id] = $wa->tema;
        }

        Date::setLocale('id');
        $tanggal = Date::now();

        return view('coc.calendar', compact('calendar', 'tema_list', 'coc_today', 'tanggal'));
    }

    public function index_bak()
    {

        $events = [];

        // ambil tema
        $tema_coc_list = TemaCoc::all();
        foreach ($tema_coc_list as $tema) {
            $event_tema = $tema->event;
            $event = Calendar::event(
                'TEMA : ' . $event_tema->title,
//                (integer)$event_tema->all_day,
                1,
                $event_tema->start->format('Y-m-d'),
                $event_tema->end->addDays(1)->format('Y-m-d'),
                $event_tema->id,
                [
//                    'url' => $event_tema->url,
//                    'url' => 'coc/tema/' . $tema->id,
//                    'url' => (Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('admin_ki') || Auth::user()->hasRole('admin_unit')) ? 'coc/tema/' . $tema->id : '',
                    'url' => (Auth::user()->can('input_coc_local')) ? 'coc/create/local/' . $tema->tema->id : '',
                    'className' => $event_tema->class_name
                ]
            );
            $events[] = $event;
        }

//        if(Auth::user()->hasRole('root')) {
//            $coc_list = Coc::all();
//            $coc_today = Coc::whereDate('tanggal_jam', '=', date('Y-m-d'))->get();
////            $coc_today = Coc::whereDate('tanggal_jam', '=', '2017-7-7')->get();
//        }else {

//        $org_user = Auth::user()->getOrgLevel()->objid;
        $arr_org_user = Auth::user()->getArrOrgLevel();
//        dd($arr_org_user);
//            dd($arr_org_user);


        // ambil coc hari ini

        // get id coc local
//            $coc_local = Coc::where('scope','local')
//                ->where('orgeh', $org_user)
//                ->whereDate('tanggal_jam', '=', date('Y-m-d'))
//                ->get(['id'])
//                ->toArray();
//        dd($coc_local);

        // get id coc local dalam satu organisasi
        $coc_local = Coc::where('scope', 'local')
            ->whereIn('orgeh', $arr_org_user)
            ->whereDate('tanggal_jam', '=', date('Y-m-d'))
            ->where('status', '!=', 'CANC')
            ->get(['id'])
            ->toArray();
//        dd($coc_local);

        // get id coc unit
        $coc_unit = Coc::where('scope', 'unit')
            ->where('company_code', Auth::user()->company_code)
            ->whereDate('tanggal_jam', '=', date('Y-m-d'))
            ->where('status', '!=', 'CANC')
            ->get(['id'])
            ->toArray();
//            dd($coc_unit);

        // get id coc nasional
//            $coc_nas = Coc::where('scope','nasional')
//                ->whereDate('tanggal_jam', '=', date('Y-m-d'))
//                ->get(['id'])
//                ->toArray();


        $coc_today = Coc::whereIn('id', $coc_local)
            ->orWhere(function ($query) use ($coc_unit) {
                $query->whereIn('id', $coc_unit);
            })
//                ->orWhere(function($query) use ($coc_nas) {
//                    $query->whereIn('id',$coc_nas);
//                })
//                ->whereIn('id',$coc_local)
//                ->whereIn('id',$coc_nas)
            ->get();

//            dd($coc_today);
//        }

        // ambil coc local
//        $coc_list = Coc::where('scope', 'local')->where('orgeh', $org_user)->get();
        $coc_list = Coc::where('scope', 'local')
            ->whereIn('orgeh', $arr_org_user)
            ->where('status', '!=', 'CANC')
            ->get();
        foreach ($coc_list as $coc) {
            $event_coc = $coc->event;
            $event = Calendar::event(
                $event_coc->title,
//                (integer)$event_coc->all_day,
                0,
                $event_coc->start,
                $event_coc->start,
                $event_coc->id,
                [
//                    'url' => $event_coc->url,
                    'url' => ($coc->checkAtendant(Auth::user()->id) || $coc->status == 'COMP') ? 'coc/event/' . $coc->id : 'coc/check-in/' . $coc->id,
                    'className' => $event_coc->class_name
                ]
            );
            $events[] = $event;
        }

        // ambil coc nasional
//        $coc_list = Coc::where('scope', 'nasional')->get();
//        foreach($coc_list as $coc) {
//            $event_coc = $coc->event;
//            $event = Calendar::event(
//                $event_coc->title,
////                (integer)$event_coc->all_day,
//                1,
//                $event_coc->start,
//                $event_coc->start,
//                $event_coc->id,
//                [
////                    'url' => $event_coc->url,
//                    'url' => ($coc->checkAtendant(Auth::user()->id))? 'coc/event/'.$coc->id : 'coc/check-in/'.$coc->id,
//                    'className' => $event_coc->class_name
//                ]
//            );
//            $events[] = $event;
//        }

        // ambil coc unit
        $coc_list = Coc::where('scope', 'unit')
            ->where('company_code', Auth::user()->company_code)
            ->where('status', '!=', 'CANC')
            ->get();
        foreach ($coc_list as $coc) {
            $event_coc = $coc->event;
            $event = Calendar::event(
                $event_coc->title,
//                (integer)$event_coc->all_day,
                1,
                $event_coc->start,
                $event_coc->start,
                $event_coc->id,
                [
//                    'url' => $event_coc->url,
                    'url' => ($coc->checkAtendant(Auth::user()->id) || $coc->status == 'COMP') ? 'coc/event/' . $coc->id : 'coc/check-in/' . $coc->id,
                    'className' => $event_coc->class_name
                ]
            );
            $events[] = $event;
        }

        if (Auth::user()->can('input_coc_local') || Auth::user()->hasRole('admin_pusat') || Auth::user()->hasRole('admin_ki') || Auth::user()->hasRole('admin_unit')) {
            // ambil materi pusat
            $materi_list = Materi::where('jenis_materi_id', '1')->get();
            foreach ($materi_list as $materi) {
                $event_materi = $materi->event;
                $event = Calendar::event(
                    'M.PST : ' . $event_materi->title,
//                (integer)$event_coc->all_day,
                    1,
                    $event_materi->start,
                    $event_materi->start,
                    $event_materi->id,
                    [
//                    'url' => $event_coc->url,
//                    'url' => ($coc->checkAtendant(Auth::user()->id))? 'coc/event/'.$coc->id : 'coc/check-in/'.$coc->id,
                        'url' => (Auth::user()->can('input_coc_local')) ? 'coc/initial/' . $materi->id : '',
                        'className' => $event_materi->class_name
                    ]
                );
                $events[] = $event;
            }

            // ambil materi GM
            $materi_list = Materi::where('jenis_materi_id', '2')
                ->where('company_code', Auth::user()->company_code)
                ->get();
//        dd($materi_list);
            foreach ($materi_list as $materi) {
                $event_materi = $materi->event;
                $event = Calendar::event(
                    'M.GM : ' . $event_materi->title,
//                (integer)$event_coc->all_day,
                    1,
                    $event_materi->start,
                    $event_materi->start,
                    $event_materi->id,
                    [
//                    'url' => $event_coc->url,
//                    'url' => ($coc->checkAtendant(Auth::user()->id))? 'coc/event/'.$coc->id : 'coc/check-in/'.$coc->id,
                        'url' => (Auth::user()->can('input_coc_local')) ? 'coc/initial/' . $materi->id : '',
                        'className' => $event_materi->class_name
                    ]
                );
                $events[] = $event;
            }

        }

//        dd($events);
//        $event = Calendar::event(
//            'Test CoC',
//            0,
//            '2017-06-05T0800',
//            '2017-06-05T1000',
//            99,
//            [
//                'url' => '',
//                'className' => 'bg-primary'
//            ]
//        );
//
//        $events[] = $event;

//        '2017-06-03T0800'

        $calendar = \Calendar::addEvents($events)
            ->setOptions([
                'firstDay' => 1
            ])->setCallbacks([

            ]);
//        $tema_list[] = 'Select Tema';
        foreach (Tema::all()->sortBy('id') as $wa) {
            $tema_list[$wa->id] = $wa->tema;
        }

        Date::setLocale('id');
        $tanggal = Date::now();

        return view('coc.calendar', compact('calendar', 'tema_list', 'coc_today', 'tanggal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
//        if ($request->is('coc/create/local')) {
//            $scope = 'local';
//        } elseif ($request->is('coc/create/unit')) {
//            $scope = 'unit';
//        } elseif ($request->is('coc/create/nasional')) {
//            $scope = 'nasional';
//        } else {
//            return redirect('coc')->with('error', 'URI not found.');
//        }
        $scope = 'local';

//        dd($scope);

//        $ba_selected = Auth::user()->business_area;
//        $bsAreaList[0] = 'Select Unit';
//        foreach (BusinessArea::all()->sortBy('id') as $wa) {
//            $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
//        }

//        return view('coc.coc_create', compact('bsAreaList', 'ba_selected'));
        $do_dont_list = PedomanPerilaku::orderBy('id', 'asc')->lists('pedoman_perilaku', 'id');
        $jenis_coc_list = JenisCoc::orderBy('id', 'asc')->lists('jenis', 'id');

        $cc_selected = Auth::user()->company_code;
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

        $ba_selected = Auth::user()->business_area;
        $bsAreaList[0] = 'Select Unit';
        foreach (BusinessArea::all()->sortBy('id') as $wa) {
            $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
        }

        $org = Auth::user()->getOrgLevel();
        $org_selected = $org->objid;
        $orgehList[$org_selected] = $org_selected . ' - ' . $org->stext;
        foreach (StrukturOrganisasi::where('sobid', Auth::user()->getOrgLevel()->objid)->orderBy('objid', 'asc')->get() as $wa) {
            $orgehList[$wa->objid] = $wa->objid . ' - ' . $wa->stext;
        }

        foreach (Tema::all()->sortBy('id') as $wa) {
            $tema_list[$wa->id] = $wa->tema;
        }

        return view('coc.coc_create3', compact('scope', 'do_dont_list', 'jenis_coc_list', 'cc_selected', 'coCodeList',
            'ba_selected', 'bsAreaList', 'org_selected', 'orgehList', 'tema_list', 'selected_tema'));
    }

    public function createLocal(Request $request)
    {
        $tema_id = 1;
        $scope = 'local';
        
        $do_dont_list = DoDont::orderBy('id', 'asc')->lists('judul', 'id');
        $jenis_coc_list = JenisCoc::orderBy('id', 'asc')->lists('jenis', 'id');

        $cc_selected = Auth::user()->company_code;
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

        $ba_selected = Auth::user()->business_area;
        $bsAreaList[0] = 'Select Unit';
        foreach (BusinessArea::all()->sortBy('id') as $wa) {
            $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
        }

        $org = Auth::user()->getOrgLevel();
        $org_selected = $org->objid;
        $orgehList[$org_selected] = $org_selected . ' - ' . $org->stext;

        if(Auth::user()->company_code == '1000'){
            $selected_kode_org = Auth::user()->getKodeDivisi();
        }
        else{
            $selected_kode_org = $org_selected;
        }

        if ($org->level == '1') {
            foreach (StrukturOrganisasi::where('status','ACTV')->where('sobid', Auth::user()->getOrgLevel()->objid)->whereNull('level')->orderBy('objid', 'asc')->get() as $wa) {
                $orgehList[$wa->objid] = $wa->objid . ' - ' . $wa->stext;
                $sub_bidang = StrukturOrganisasi::where('status','ACTV')->where('sobid', $wa->objid)->orderBy('objid', 'asc')->get();
                if ($sub_bidang != null) {
                    foreach ($sub_bidang as $wa2) {
                        $orgehList[$wa2->objid] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $wa2->objid . ' - ' . $wa2->stext;
                    }
                }
            }

        } else {
            foreach (StrukturOrganisasi::where('status','ACTV')->where('sobid', Auth::user()->getOrgLevel()->objid)->orderBy('objid', 'asc')->get() as $wa) {
                $orgehList[$wa->objid] = $wa->objid . ' - ' . $wa->stext;
                $sub_bidang = StrukturOrganisasi::where('status','ACTV')->where('sobid', $wa->objid)->orderBy('objid', 'asc')->get();
                if ($sub_bidang != null) {
                    foreach ($sub_bidang as $wa2) {
                        $orgehList[$wa2->objid] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $wa2->objid . ' - ' . $wa2->stext;
                    }
                }
            }
        }

        foreach (Tema::all()->sortBy('id') as $wa) {
            $tema_list[$wa->id] = $wa->tema;
        }

        // get data isu nasional
        $isu_nasional_coc = IsuNasionalCoC::where('admin_id', Auth::user()->id)
                                            ->where('orgeh', $selected_kode_org)
                                            ->where('status', 'ACTV')
                                            ->whereYear('created_at', '=', date('Y'))
                                            ->pluck('isu_nasional_id');
        $isu_nasional_list = IsuNasional::where('status','ACTV')->orderBy('jenis_isu_nasional_id','asc')->get();

        $isu_nasional_select = IsuNasional::where('is_default', 1)->first();

        //jika null, reset dan pilih isu nasional pertama
        if($isu_nasional_select == null){
            // delete history isu nasional
            $old_issue = IsuNasionalCoC::where('admin_id', Auth::user()->id)
                            ->where('orgeh', $selected_kode_org)
                            ->where('status', 'ACTV')
                            ->whereYear('created_at', '=', date('Y'))
                            ->get();

            foreach ($old_issue as $isu) {
                $isu->status = 'DEL';
                $isu->save();
            }

            $isu_nasional_select = IsuNasional::where('status','ACTV')->orderBy('id','asc')->first();
        }

        // get data pelanggaran
        $pelanggaran_coc = PelanggaranCoc::where('admin_id', Auth::user()->id)->where('orgeh', $selected_kode_org)
                                            ->where('status', 'ACTV')
                                            ->whereYear('created_at', '=', date('Y'))
                                            ->pluck('pelanggaran_id');
        $pelanggaran_list = Pelanggaran::whereNotIn('id', $pelanggaran_coc)->orderBy('id','asc')->get();
        $pelanggaran_history = Pelanggaran::whereIn('id', $pelanggaran_coc)->orderBy('id','asc')->get();

        $organisasi_selected = StrukturOrganisasi::where('objid', $selected_kode_org)->first();

        $random_value = rand(1,5);

        return view('coc.create_coc_local_wizard_v2', compact('scope', 'jenis_coc_list', 'cc_selected', 'coCodeList',
            'ba_selected', 'bsAreaList', 'org_selected', 'orgehList', 'materi_list', 'selected_tema',
            'tema_id', 'tema_list', 'pelanggaran_list', 'pelanggaran_history', 'organisasi_selected', 'random_value','selected_kode_org', 'isu_nasional_list', 'isu_nasional_select'));
    }

    // CREATE COC LOCAL

    public function createLocalFromTema($tema_id)
    {
        $scope = 'local';

        $do_dont_list = DoDont::orderBy('id', 'asc')->lists('judul', 'id');
        $jenis_coc_list = JenisCoc::orderBy('id', 'asc')->lists('jenis', 'id');

        $cc_selected = Auth::user()->company_code;
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

        $ba_selected = Auth::user()->business_area;
        $bsAreaList[0] = 'Select Unit';
        foreach (BusinessArea::all()->sortBy('id') as $wa) {
            $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
        }

        $org = Auth::user()->getOrgLevel();
        $org_selected = $org->objid;
        $orgehList[$org_selected] = $org_selected . ' - ' . $org->stext;

        if(Auth::user()->company_code == '1000'){
            $selected_kode_org = Auth::user()->getKodeDivisi();
        }
        else{
            $selected_kode_org = $org_selected;
        }

        if ($org->level == '1') {
            foreach (StrukturOrganisasi::where('status','ACTV')->where('sobid', Auth::user()->getOrgLevel()->objid)->whereNull('level')->orderBy('objid', 'asc')->get() as $wa) {
                $orgehList[$wa->objid] = $wa->objid . ' - ' . $wa->stext;
                $sub_bidang = StrukturOrganisasi::where('status','ACTV')->where('sobid', $wa->objid)->orderBy('objid', 'asc')->get();
                if ($sub_bidang != null) {
                    foreach ($sub_bidang as $wa2) {
                        $orgehList[$wa2->objid] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $wa2->objid . ' - ' . $wa2->stext;
                    }
                }
            }

        } else {
            foreach (StrukturOrganisasi::where('status','ACTV')->where('sobid', Auth::user()->getOrgLevel()->objid)->orderBy('objid', 'asc')->get() as $wa) {
                $orgehList[$wa->objid] = $wa->objid . ' - ' . $wa->stext;
                $sub_bidang = StrukturOrganisasi::where('status','ACTV')->where('sobid', $wa->objid)->orderBy('objid', 'asc')->get();
                if ($sub_bidang != null) {
                    foreach ($sub_bidang as $wa2) {
                        $orgehList[$wa2->objid] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $wa2->objid . ' - ' . $wa2->stext;
                    }
                }
            }
        }

        foreach (Tema::all()->sortBy('id') as $wa) {
            $tema_list[$wa->id] = $wa->tema;
        }

        // get data isu nasional
        $isu_nasional_coc = IsuNasionalCoC::where('admin_id', Auth::user()->id)
                                            ->where('orgeh', $selected_kode_org)
                                            ->where('status', 'ACTV')
                                            ->whereYear('created_at', '=', date('Y'))
                                            ->pluck('isu_nasional_id');
        $isu_nasional_list = IsuNasional::where('status','ACTV')->orderBy('jenis_isu_nasional_id','asc')->get();
        $isu_nasional_select = IsuNasional::where('is_default', 1)->first();

        //jika null, reset dan pilih isu nasional pertama
        if($isu_nasional_select == null){
            // delete history isu nasional
            $old_issue = IsuNasionalCoC::where('admin_id', Auth::user()->id)
                            ->where('orgeh', $selected_kode_org)
                            ->where('status', 'ACTV')
                            ->whereYear('created_at', '=', date('Y'))
                            ->get();

            foreach ($old_issue as $isu) {
                $isu->status = 'DEL';
                $isu->save();
            }

            $isu_nasional_select = IsuNasional::where('status','ACTV')->orderBy('id','asc')->first();
        }

        // get data pelanggaran
        $pelanggaran_coc = PelanggaranCoc::where('admin_id', Auth::user()->id)->where('orgeh', $selected_kode_org)
            ->where('status', 'ACTV')
            ->whereYear('created_at', '=', date('Y'))
            ->pluck('pelanggaran_id');
        $pelanggaran_list = Pelanggaran::whereNotIn('id', $pelanggaran_coc)->orderBy('id','asc')->get();
        $pelanggaran_history = Pelanggaran::whereIn('id', $pelanggaran_coc)->orderBy('id','asc')->get();

        $organisasi_selected = StrukturOrganisasi::where('objid', $selected_kode_org)->first();

        $random_value = rand(1,5);

        return view('coc.create_coc_local_wizard_v2', compact('scope', 'jenis_coc_list', 'cc_selected', 'coCodeList',
            'ba_selected', 'bsAreaList', 'org_selected', 'orgehList', 'materi_list', 'selected_tema', 'tema_id', 'tema_list', 'pelanggaran_list',
            'organisasi_selected', 'pelanggaran_history', 'random_value', 'selected_kode_org', 'isu_nasional_list', 'isu_nasional_select'));
    }

    public function createMateri(Request $request)
    {
//        if($request->is('coc/create/local')){
//            $scope = 'local';
//        }
//        else
        if ($request->is('coc/create/materi/gm')) {
            $scope = 'gm';
        } elseif ($request->is('coc/create/materi/nasional')) {
            $scope = 'nasional';
        } else {
            return redirect('coc')->with('error', 'URI not found.');
        }
//        $ba_selected = Auth::user()->business_area;
//        $bsAreaList[0] = 'Select Unit';
//        foreach (BusinessArea::all()->sortBy('id') as $wa) {
//            $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
//        }

//        return view('coc.coc_create', compact('bsAreaList', 'ba_selected'));
        $do_dont_list = PedomanPerilaku::orderBy('id', 'asc')->lists('pedoman_perilaku', 'id');
        $jenis_coc_list = JenisCoc::orderBy('id', 'asc')->lists('jenis', 'id');
        return view('coc.materi_create', compact('scope', 'do_dont_list', 'jenis_coc_list'));
    }

    // CREATE COC FROM MATERI

    public function createFromMateri(Request $request, $materi_id)
    {
        $materi = Materi::findOrFail($materi_id);
        $do_dont_list = DoDont::orderBy('id', 'asc')->lists('judul', 'id');
        $jenis_coc_list = JenisCoc::orderBy('id', 'asc')->lists('jenis', 'id');
        $tanggal_materi = $materi->event->start;

        $cc_selected = Auth::user()->company_code;
        $coCodeList[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

        $ba_selected = Auth::user()->business_area;
        $bsAreaList[0] = 'Select Unit';
        foreach (BusinessArea::all()->sortBy('id') as $wa) {
            $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
        }

        $org = Auth::user()->getOrgLevel();

        $org_selected = $org->objid;
        if(Auth::user()->company_code == '1000'){
            $selected_kode_org = Auth::user()->getKodeDivisi();
        }
        else{
            $selected_kode_org = $org_selected;
        }

        $orgehList[$org_selected] = $org_selected . ' - ' . $org->stext;

        if ($org->level == '1') {
            foreach (StrukturOrganisasi::where('status','ACTV')->where('sobid', Auth::user()->getOrgLevel()->objid)->whereNull('level')->orderBy('objid', 'asc')->get() as $wa) {
                $orgehList[$wa->objid] = $wa->objid . ' - ' . $wa->stext;
                $sub_bidang = StrukturOrganisasi::where('status','ACTV')->where('sobid', $wa->objid)->orderBy('objid', 'asc')->get();
                if ($sub_bidang != null) {
                    foreach ($sub_bidang as $wa2) {
                        $orgehList[$wa2->objid] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $wa2->objid . ' - ' . $wa2->stext;
                    }
                }
            }
        } else {
            foreach (StrukturOrganisasi::where('status','ACTV')->where('sobid', Auth::user()->getOrgLevel()->objid)->orderBy('objid', 'asc')->get() as $wa) {
                $orgehList[$wa->objid] = $wa->objid . ' - ' . $wa->stext;
                $sub_bidang = StrukturOrganisasi::where('status','ACTV')->where('sobid', $wa->objid)->orderBy('objid', 'asc')->get();
                if ($sub_bidang != null) {
                    foreach ($sub_bidang as $wa2) {
                        $orgehList[$wa2->objid] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $wa2->objid . ' - ' . $wa2->stext;
                    }
                }
            }
        }

        foreach (Tema::all()->sortBy('id') as $wa) {
            $tema_list[$wa->id] = $wa->tema;
        }

        // get data isu nasional
        $isu_nasional_coc = IsuNasionalCoC::where('admin_id', Auth::user()->id)
                                            ->where('orgeh', $selected_kode_org)
                                            ->where('status', 'ACTV')
                                            ->whereYear('created_at', '=', date('Y'))
                                            ->pluck('isu_nasional_id');
        $isu_nasional_list = IsuNasional::where('status','ACTV')->orderBy('jenis_isu_nasional_id','asc')->get();
        $isu_nasional_select = IsuNasional::where('is_default', 1)->first();

        //jika null, reset dan pilih isu nasional pertama
        if($isu_nasional_select == null){
            // delete history isu nasional
            $old_issue = IsuNasionalCoC::where('admin_id', Auth::user()->id)
                            ->where('orgeh', $selected_kode_org)
                            ->where('status', 'ACTV')
                            ->whereYear('created_at', '=', date('Y'))
                            ->get();

            foreach ($old_issue as $isu) {
                $isu->status = 'DEL';
                $isu->save();
            }

            $isu_nasional_select = IsuNasional::where('status','ACTV')->orderBy('id','asc')->first();
        }

        // get data pelanggaran
        $pelanggaran_coc = PelanggaranCoc::where('admin_id', Auth::user()->id)->where('orgeh', $selected_kode_org)
            ->where('status', 'ACTV')
            ->whereYear('created_at', '=', date('Y'))
            ->pluck('pelanggaran_id');
        $pelanggaran_list = Pelanggaran::whereNotIn('id', $pelanggaran_coc)->orderBy('id','asc')->get();
        $pelanggaran_history = Pelanggaran::whereIn('id', $pelanggaran_coc)->orderBy('id','asc')->get();

        $organisasi_selected = StrukturOrganisasi::where('objid', $selected_kode_org)->first();

        $random_value = rand(1,5);

        return view('coc.create_coc_materi_wizard_v2', compact('materi', 'jenis_coc_list', 'cc_selected', 'coCodeList',
            'ba_selected', 'bsAreaList', 'org_selected', 'orgehList', 'pelanggaran_list',
            'pelanggaran_history', 'organisasi_selected', 'tanggal_materi', 'random_value', 'selected_kode_org',
            'isu_nasional_list', 'isu_nasional_select'));
    }

    public function store(JadwalKIRequest $request)
    {
        $file = $request->file('materi');
//        if ($file == null) {
//            return redirect('coc/create/')->with('warning', 'File materi belum dipilih');
//        }
        if ($file != null) {
            $extension = strtolower($file->getClientOriginalExtension());
            if ($extension != 'pdf') {
                return redirect('coc/create/local')->with('warning', 'File yang diupload bukan berekstensi PDF.');
            }
        }
//        $size = $file->getSize();
//        if($size>1000000){
//            return  redirect('coc/create/')->with('warning', 'Ukuran file yang diupload melebihi 1MB');
//        }

//        dd($request);

//        $coc = Coc::findOrFail($id);

        $tanggal_coc = Carbon::parse($request->tanggal_coc . ' ' . $request->jam);

        // cek tema pada tanggal tsb
        $thematic = TemaCoc::where('start_date', '<=', $tanggal_coc)
            ->where('end_date', '>=', $tanggal_coc)
            ->first();

//        dd($thematic);

        // jika belum ada tema
        if ($thematic == null)
            return redirect('coc')->with('warning', 'Tema belum tersedia. Mohon tunggu sampai ada tema dari Kantor Pusat.');

        $lokasi = $request->lokasi;
        $all_day = '0';
        $bg = 'bg-primary';
//        }

        $tanggal_coc = Carbon::parse($request->tanggal_coc . ' ' . $request->jam);

        // jika sudah ada tema
        $event = new Event();
        $event->title = $request->judul_coc;
        $event->all_day = $all_day;
        $event->start = $tanggal_coc;
//        $event->end       = $tanggal_coc;
//        $event->url       = '';
//    $event->color   = '#1bb99a';
        $event->class_name = $bg;

        $event->save();

        // create materi
        $materi = new Materi();
        $materi->event_id = $event->id;
        $materi->tema_id = $thematic->tema_id;
        $materi->pernr_penulis = $request->pernr_penulis;
        $materi->judul = $request->judul_materi;
        $materi->deskripsi = $request->deskripsi;
        $materi->jenis_materi_id = '5';

//        $materi->company_code = Auth::user()->company_code;
//        $materi->business_area = Auth::user()->business_area;
//        $materi->orgeh = Auth::user()->getOrgLevel()->objid;

        $materi->company_code = $request->company_code;
        $materi->business_area = $request->business_area;
        $materi->orgeh = $request->orgeh;

        $materi->save();


        $coc = new Coc();
        $coc->event_id = $event->id;
        $coc->tema_id = $materi->tema->id;
        $coc->jenis_coc_id = $request->jenis_coc_id;
//        $coc->pemateri_id   = $request->pemateri_id;
        $coc->admin_id = Auth::user()->id;
        $coc->tanggal_jam = $tanggal_coc;
        $coc->judul = $request->judul_coc;
        $coc->deskripsi = $materi->deskripsi;
        $coc->pernr_pemateri = $request->pernr_leader;
        $coc->lokasi = $lokasi;

//        $coc->company_code  = Auth::user()->company_code;
//        $coc->business_area = Auth::user()->business_area;
//        $coc->orgeh         = Auth::user()->getOrgLevel()->objid;

        $coc->company_code = $request->company_code;
        $coc->business_area = $request->business_area;
        $coc->orgeh = $request->orgeh;

        $coc->materi_id = $materi->id;
        $coc->pedoman_perilaku_id = $request->pedoman_perilaku_id;
        $coc->pernr_leader = $request->pernr_leader;

        $coc->scope = 'local';

        $coc->tema_id_unit = $request->tema_id_unit;
        $coc->jml_peserta = $request->jml_peserta;

        $coc->save();

        if ($file != null) {
            $attachment = new AttachmentMateri();
            $attachment->materi_id = $materi->id;
            $attachment->judul = $materi->judul;
            $attachment->filename = $file->getClientOriginalName();
            $attachment->save();

            Storage::put($materi->business_area . '/attachment_materi/' . $file->getClientOriginalName(), File::get($file));
        }

        return redirect('coc')->with('success', 'Jadwal CoC berhasil disimpan.');
    }

    // STORE COC LOCAL

    public function storeLocal(JadwalLocalRequest $request)
    {
        $tanggal_coc = Carbon::parse($request->tanggal_coc . ' ' . $request->jam);
        if ($tanggal_coc->isMonday()) {
            return redirect()->back()->withErrors(['tanggal' => 'CoC local tidak boleh dijadwalkan hari Senin. Hari Senin hanya untuk CoC Nasional.'])->withInput();
        }
        if (count($request->sipp) == 0) {
            return redirect()->back()->withErrors(['tata_nilai' => 'Pilih minimal 1 Tata Nilai.'])->withInput();
        }

        $file = $request->file('materi');
        if ($file != null) {
            $extension = strtolower($file->getClientOriginalExtension());
            if ($extension != 'pdf') {
                return redirect('coc/create/local')->with('warning', 'File yang diupload bukan berekstensi PDF.');
            }
        }

        // cek tema pada tanggal tsb
        $thematic = TemaCoc::where('start_date', '<=', $tanggal_coc)
            ->where('end_date', '>=', $tanggal_coc)
            ->first();

        // jika belum ada tema
        if ($thematic == null)
            return redirect('coc')->with('warning', 'Tema dari Kantor Pusat belum tersedia. Mohon tunggu sampai ada tema dari Kantor Pusat.');

        $lokasi = $request->lokasi;
        $all_day = '0';
        $bg = 'bg-primary';

        $tanggal_coc = Carbon::parse($request->tanggal_coc . ' ' . $request->jam);

        // jika sudah ada tema pusat
        $event = new Event();
        $event->title = $request->judul_coc;
        $event->all_day = $all_day;
        $event->start = $tanggal_coc;
        $event->class_name = $bg;

        $event->save();

        // get pernr leader
        $pernr_leader = PA0032::where('nip', $request->nip_pemateri)->first();
        // if pernr leader found
        if ($pernr_leader != null) {
            $pernr_leader = $pernr_leader->pernr;
        } else {
            $pernr_leader = '';
        }

        $coc = new Coc();
        $coc->event_id = $event->id;
        $coc->tema_id = $request->tema_id;
        $coc->jenis_coc_id = 5; // Local
        $coc->admin_id = Auth::user()->id;
        $coc->tanggal_jam = $tanggal_coc;
        $coc->judul = $request->judul_coc;
        $coc->pernr_pemateri = $pernr_leader;
        $coc->lokasi = $lokasi;

        $coc->company_code = $request->company_code;
        $coc->business_area = $request->business_area;
        $coc->orgeh = $request->orgeh;

        $coc->pernr_leader = $pernr_leader;
        $coc->scope = 'local';

        $coc->tema_id_unit = $request->tema_id;
        $coc->jml_peserta = $request->jml_peserta;
        $coc->misi = $request->misi;

        // akhlak
        $coc->akhlak_amanah = (in_array('1', $request->sipp)) ? '1' : '';
        $coc->akhlak_kompeten = (in_array('2', $request->sipp)) ? '1' : '';
        $coc->akhlak_harmonis = (in_array('3', $request->sipp)) ? '1' : '';
        $coc->akhlak_loyal = (in_array('4', $request->sipp)) ? '1' : '';
        $coc->akhlak_adaptif = (in_array('5', $request->sipp)) ? '1' : '';
        $coc->akhlak_kolaboratif = (in_array('6', $request->sipp)) ? '1' : '';

        // holding
        if(!($request->company_code == '1200' || $request->company_code == '1300')){
            // save plans + delegation leader for autocomplete
            $leader = StrukturJabatan::where('nip', $request->nip_leader)->first();
            $jenjang = $leader->getJenjangJabatan();

            if($jenjang == null){
                return redirect()->back()->with('error', 'Jenjang leader tidak ditemukan di sistem. Silakan hubungi Administrator Pusat.');
            }

            $coc->level_unit = $leader->getLevelUnit();
            $coc->jenjang_id = $jenjang->id;
            $coc->plans_leader = @$leader->getDefinitive();
            $coc->delegation_leader = $leader->plans;
        }
        // shap
        else{
            $coc->level_unit = '1';
            $coc->jenjang_id = '1';
            $coc->plans_leader = '';
            $coc->delegation_leader = '';
        }

        $coc->nip_pemateri = $request->nip_leader;
        $coc->nip_leader = $request->nip_leader;

        $coc->save();

        // save isu nasional
        $isu_nasional_coc = new IsuNasionalCoc();
        $isu_nasional_coc->admin_id = $coc->admin_id;
        $isu_nasional_coc->orgeh = $coc->orgeh;
        $isu_nasional_coc->coc_id = $coc->id;
        $isu_nasional_coc->isu_nasional_id = $request->isu_nasional_id;
        $isu_nasional_coc->save();

        // save pelanggaran
        $pelanggaran = Pelanggaran::find($request->pelanggaran);
        $pelanggaran_coc = new PelanggaranCoc();
        $pelanggaran_coc->admin_id = $coc->admin_id;
        $pelanggaran_coc->orgeh = $coc->orgeh;
        $pelanggaran_coc->coc_id = $coc->id;
        $pelanggaran_coc->pelanggaran_id = $request->pelanggaran;
        $pelanggaran_coc->jenis_pelanggaran_id = $pelanggaran->jenisPelanggaran->id;
        $pelanggaran_coc->save();

        Activity::log('Create jadwal CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y H:i') . '); ID: ' . $coc->id, 'success');

        // notifikasi ke para peserta CoC

        $tema = $coc->tema->tema;
        $jenis_coc = 'Lokal';
        $color = 'primary';
        $judul = $coc->judul;
        $leader = @$coc->leader->name;
        $jabatan_leader = @$coc->leader->jabatan;
        $lokasi = $coc->lokasi;
        $tanggal = $coc->tanggal_jam->format('d-m-Y');
        $jam = $coc->tanggal_jam->format('H:i');
        $organisasi = $coc->organisasi->stext;

        // cari organisasi2 di bawah orgeh coc
        $coc_orgeh = StrukturOrganisasi::where('objid', $coc->orgeh)->first();
        $arr_orgeh = $coc_orgeh->getChildren();

        // cari nip dari organisasi2 di baawah orgeh coc
        // $arr_nip = StrukturJabatan::whereIn('orgeh', $arr_orgeh)->pluck('nip')->toArray();;

        // cari user berdasarkan NIP
        $user_list = User::where('status', 'ACTV')
            // ->whereIn('nip', $arr_nip)
            ->whereIn('orgeh', $arr_orgeh)
            ->get();

        foreach ($user_list as $user) {
            $notif = new Notification();
            $notif->from = Auth::user()->username2;
            $notif->to = $user->username2;
            $notif->user_id_from = Auth::user()->id;
            $notif->user_id_to = $user->id;
            $notif->subject = 'CoC ' . $jenis_coc;
            $notif->color = $color;
            $notif->message = '"' . $judul . '". Leader: ' . $leader . ' (' . $jabatan_leader . '). Organisasi: ' . $organisasi . '. Lokasi: ' . $lokasi . '. Tanggal/Jam: ' . $tanggal . '/' . $jam . '.';
            $notif->url = 'coc/check-in/' . $coc->id;
            $notif->save();

            $mail = new MailLog();
            $mail->to = $user->email;
            $mail->to_name = $user->name;
            $mail->subject = '[KOMANDO] CoC ' . $jenis_coc . ' di Unit Anda';
            $mail->file_view = 'emails.coc_created';
            $mail->message = '"' . $judul . '". Leader: ' . $leader . ' (' . $jabatan_leader . '). Organisasi: ' . $organisasi . '. Lokasi: ' . $lokasi . '. Tanggal/Jam: ' . $tanggal . '/' . $jam . '.';
            $mail->status = 'CRTD';
            $mail->parameter = '{"tema":"' . $tema . '","jenis":"' . $jenis_coc . '","judul":"' . $judul . '","leader":"' . $leader . '","jabatan_leader":"' . $jabatan_leader . '","organisasi":"' . $organisasi . '","lokasi":"' . $lokasi . '","tanggal":"' . $tanggal . '","jam":"' . $jam . '"}';
            $mail->notification_id = $notif->id;
            $mail->jenis = '3';

            $mail->save();
        }

        return redirect('coc')->with('success', 'Jadwal CoC berhasil disimpan. Admin CoC memiliki waktu '.env('SUBDAYS_AUTOCOMPLETE',5).' hari setelah tanggal CoC untuk melakukan Complete. Jika setelah '.env('SUBDAYS_AUTOCOMPLETE',5).' hari status CoC masih OPEN, sistem akan melakukan Complete secara otomatis.');
    }

    // STORE COC FROM MATERI

    public function storeFromMateri(JadwalRequest $request, $materi_id)
    {
        if (count($request->sipp) == 0) {
            return redirect()->back()->withErrors(['tata_nilai' => 'Pilih minimal 1 Tata Nilai.'])->withInput();
        }

        $materi = Materi::findOrFail($materi_id);
        if ($materi->jenis_materi_id == '1') {
            $lokasi = $request->lokasi;
            $all_day = '1';
            $bg = 'bg-danger';
            $jenis_coc = 'Nasional';
            $jenis_coc_id = '1';
            $color = 'pink';
        } elseif ($materi->jenis_materi_id == '2') {
            $lokasi = $request->lokasi;
            $all_day = '1';
            $bg = 'bg-warning';
            $jenis_coc = 'General Manager';
            $jenis_coc_id = '2';
            $color = 'warning';
        } 
        else {
            $lokasi = $request->lokasi;
            $all_day = '0';
            $bg = 'bg-primary';
            $jenis_coc = 'Lokal';
            $jenis_coc_id = '5';
            $color = 'primary';
        }

        $tanggal_coc = Carbon::parse($request->tanggal_coc . ' ' . $request->jam);

        // cek tema pada tanggal tsb
        $thematic = TemaCoc::where('start_date', '<=', $tanggal_coc)
            ->where('end_date', '>=', $tanggal_coc)
            ->first();

        // jika belum ada tema
        if ($thematic == null)
            return redirect('coc')->with('warning', 'Tema dari Kantor Pusat belum tersedia. Mohon tunggu sampai ada tema dari Kantor Pusat.');

        // jika sudah ada tema
        $event = new Event();
        $event->title = $request->judul;
        $event->all_day = $all_day;
        $event->start = $tanggal_coc;
        $event->class_name = $bg;

        $event->save();

        // get pernr leader
        $pernr_leader = PA0032::where('nip', $request->nip_pemateri)->first();
        // if pernr leader found
        if ($pernr_leader != null) {
            $pernr_leader = $pernr_leader->pernr;
        } else {
            $pernr_leader = '';
        }

        $coc = new Coc();
        $coc->event_id = $event->id;
        $coc->tema_id = $materi->tema->id;
        $coc->jenis_coc_id = $jenis_coc_id;
        $coc->admin_id = Auth::user()->id;
        $coc->tanggal_jam = $tanggal_coc;
        $coc->judul = $request->judul;
        $coc->pernr_pemateri = $pernr_leader;
        $coc->lokasi = $lokasi;

        $coc->company_code = $request->company_code;
        $coc->business_area = $request->business_area;
        $coc->orgeh = $request->orgeh;

        $coc->materi_id = $materi->id;
        $coc->pernr_leader = $pernr_leader;

        $coc->scope = 'local';

        $coc->tema_id_unit = $materi->tema->id;
        $coc->jml_peserta = $request->jml_peserta;

        $coc->misi = $request->misi;

        // akhlak
        $coc->akhlak_amanah = (in_array('1', $request->sipp)) ? '1' : '';
        $coc->akhlak_kompeten = (in_array('2', $request->sipp)) ? '1' : '';
        $coc->akhlak_harmonis = (in_array('3', $request->sipp)) ? '1' : '';
        $coc->akhlak_loyal = (in_array('4', $request->sipp)) ? '1' : '';
        $coc->akhlak_adaptif = (in_array('5', $request->sipp)) ? '1' : '';
        $coc->akhlak_kolaboratif = (in_array('6', $request->sipp)) ? '1' : '';

        // holding
        if(!($request->company_code == '1200' || $request->company_code == '1300')){
            // save plans + delegation leader for autocomplete
            $leader = StrukturJabatan::where('nip', $request->nip_pemateri)->first();
            $jenjang = $leader->getJenjangJabatan();
            if($jenjang == null){
                return redirect()->back()->with('error', 'Jenjang leader tidak ditemukan di sistem. Silakan hubungi Administrator Pusat.');
            }
            $coc->level_unit = $leader->getLevelUnit();
            $coc->jenjang_id = $jenjang->id;
            $coc->plans_leader = @$leader->getDefinitive();
            $coc->delegation_leader = $leader->plans;
        }
        // shap
        else{
            $coc->level_unit = '1';
            $coc->jenjang_id = '1';
            $coc->plans_leader = '';
            $coc->delegation_leader = '';
        }

        $coc->nip_pemateri = $request->nip_pemateri;
        $coc->nip_leader = $request->nip_pemateri;

        $coc->save();

        // save isu nasional
        $isu_nasional_coc = new IsuNasionalCoc();
        $isu_nasional_coc->admin_id = $coc->admin_id;
        $isu_nasional_coc->orgeh = $coc->orgeh;
        $isu_nasional_coc->coc_id = $coc->id;
        $isu_nasional_coc->isu_nasional_id = $request->isu_nasional_id;
        $isu_nasional_coc->save();

        // save pelanggaran
        $pelanggaran = Pelanggaran::find($request->pelanggaran);
        $pelanggaran_coc = new PelanggaranCoc();
        $pelanggaran_coc->admin_id = $coc->admin_id;
        $pelanggaran_coc->orgeh = $coc->orgeh;
        $pelanggaran_coc->coc_id = $coc->id;
        $pelanggaran_coc->pelanggaran_id = $request->pelanggaran;
        $pelanggaran_coc->jenis_pelanggaran_id = $pelanggaran->jenisPelanggaran->id;
        $pelanggaran_coc->save();

        Activity::log('Create jadwal CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y H:i') . '); ID: ' . $coc->id, 'success');

        // notifikasi ke para peserta CoC

        $tema = $coc->tema->tema;
        $judul = $coc->judul;
        $leader = @$coc->leader->name;
        $jabatan_leader = @$coc->leader->jabatan;
        $lokasi = $coc->lokasi;
        $tanggal = $coc->tanggal_jam->format('d-m-Y');
        $jam = $coc->tanggal_jam->format('H:i');
        $organisasi = $coc->organisasi->stext;

        // cari organisasi2 di bawah orgeh coc
        $coc_orgeh = StrukturOrganisasi::where('objid', $coc->orgeh)->first();
        $arr_orgeh = $coc_orgeh->getChildren();

        // cari nip dari organisasi2 di baawah orgeh coc
        // $arr_nip = StrukturJabatan::whereIn('orgeh', $arr_orgeh)->pluck('nip')->toArray();

        // cari user berdasarkan NIP
        $user_list = User::where('status', 'ACTV')
            // ->whereIn('nip', $arr_nip)
            ->whereIn('orgeh', $arr_orgeh)
            ->get();

        foreach ($user_list as $user) {
            $notif = new Notification();
            $notif->from = Auth::user()->username2;
            $notif->to = $user->username2;
            $notif->user_id_from = Auth::user()->id;
            $notif->user_id_to = $user->id;
            $notif->subject = 'CoC ' . $jenis_coc;
            $notif->color = $color;
            $notif->message = '"' . $judul . '". Leader: ' . $leader . ' (' . $jabatan_leader . '). Organisasi: ' . $organisasi . '. Lokasi: ' . $lokasi . '. Tanggal/Jam: ' . $tanggal . '/' . $jam . '.';
            $notif->url = 'coc/check-in/' . $coc->id;
            $notif->save();

            $mail = new MailLog();
            if($user->email != ''){
                $mail->to = $user->email;
                $mail->to_name = $user->name;
                $mail->subject = '[KOMANDO] CoC ' . $jenis_coc . ' di Unit Anda';
                $mail->file_view = 'emails.coc_created';
                $mail->message = '"' . $judul . '". Leader: ' . $leader . ' (' . $jabatan_leader . '). Organisasi: ' . $organisasi . '. Lokasi: ' . $lokasi . '. Tanggal/Jam: ' . $tanggal . '/' . $jam . '.';
                $mail->status = 'CRTD';
                $mail->parameter = '{"tema":"' . $tema . '","jenis":"' . $jenis_coc . '","judul":"' . $judul . '","leader":"' . $leader . '","jabatan_leader":"' . $jabatan_leader . '","organisasi":"' . $organisasi . '","lokasi":"' . $lokasi . '","tanggal":"' . $tanggal . '","jam":"' . $jam . '"}';
                $mail->notification_id = $notif->id;
                $mail->jenis = '3';

                $mail->save();
            }
        }

        return redirect('coc')->with('success', 'Jadwal CoC berhasil disimpan. Admin CoC diberi waktu '.env('SUBDAYS_AUTOCOMPLETE',5).' hari setelah tanggal CoC untuk melakukan Complete. Jika setelah '.env('SUBDAYS_AUTOCOMPLETE',5).' hari status CoC masih OPEN, sistem akan melakukan Complete secara otomatis.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
//    public function store(Request $request, $scope)
//    {
//        $file = $request->file('materi');
////        if ($file == null) {
////            return redirect('coc/create/')->with('warning', 'File materi belum dipilih');
////        }
//        if ($file != null) {
//            $extension = strtolower($file->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('coc/create/'.$scope)->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }
//        }
////        $size = $file->getSize();
////        if($size>1000000){
////            return  redirect('coc/create/')->with('warning', 'Ukuran file yang diupload melebihi 1MB');
////        }
//
////        dd($request);
//
////        $coc = Coc::findOrFail($id);
//
//        $tanggal_coc   = Carbon::parse($request->tanggal_coc.' '.$request->jam);
//
//        // cek tema pada tanggal tsb
//        $thematic = TemaCoc::where('start_date', '<=', $tanggal_coc)
//                            ->where('end_date', '>=', $tanggal_coc)
//                            ->first();
//
//        // jika belum ada tema
//        if($thematic==null)
//            return redirect('coc')->with('warning', 'Tema belum tersedia. Mohon tunggu sampai ada tema dari Kantor Pusat.');
//
//        // jika sudah ada tema
//        $event            = new Event();
//        $event->title     = $request->topik;
//        $event->all_day   = '0';
//        $event->start     = $tanggal_coc;
////        $event->end       = $tanggal_coc;
////        $event->url       = '';
////    $event->color   = '#1bb99a';
//        $event->class_name = 'bg-primary';
//
//        $event->save();
//
//        $coc = new Coc();
//        $coc->event_id      = $event->id;
//        $coc->tema_id       = $thematic->tema->id;
////        $coc->pemateri_id   = $request->pemateri_id;
//        $coc->admin_id      = Auth::user()->id;
//        $coc->tanggal_jam   = $tanggal_coc;
//        $coc->judul         = $request->topik;
//        $coc->deskripsi     = $request->deskripsi;
//        $coc->pernr_pemateri = $request->pernr_pemateri;
//        $coc->lokasi        = $request->lokasi;
//
////        $pemateri = StrukturJabatan::where('pernr', $coc->pernr_pemateri)->first();
////        dd($pemateri->strukturOrganisasi->getOrgLevel());
//
//        $coc->company_code  = Auth::user()->company_code;
//        $coc->business_area = Auth::user()->business_area;
//        $coc->orgeh         = Auth::user()->getOrgLevel()->objid;
//
////        $coc->company_code  = @$pemateri->strukturOrganisasi->getOrgLevel()->hrp1008->bukrs;
////        $coc->business_area = @$pemateri->strukturOrganisasi->getOrgLevel()->hrp1008->gsber;
////        $coc->orgeh         = @$pemateri->strukturOrganisasi->getOrgLevel()->objid;
//
//        $coc->scope = $scope;
//
//        $coc->save();
//
//        if($file != null) {
//            $attachment = new Attachment();
//            $attachment->coc_id = $coc->id;
//            $attachment->judul = $coc->judul;
//            $attachment->filename = $file->getClientOriginalName();
//            $attachment->save();
//
//            Storage::put($coc->business_area . '/attachment/' . $file->getClientOriginalName(), File::get($file));
//        }
//
//        return redirect('coc/event/'.$coc->id)->with('success', 'CoC berhasil disimpan.');
//    }

    public function _store(Request $request)
    {
        if ($request->is('coc/create/local')) {
            $scope = 'local';
        } elseif ($request->is('coc/create/unit')) {
            $scope = 'unit';
        } elseif ($request->is('coc/create/nasional')) {
            $scope = 'nasional';
        } else {
            return redirect('coc')->with('error', 'URI not found.');
        }

        $file = $request->file('materi');
//        if ($file == null) {
//            return redirect('coc/create/')->with('warning', 'File materi belum dipilih');
//        }
        if ($file != null) {
            $extension = strtolower($file->getClientOriginalExtension());
            if ($extension != 'pdf') {
                return redirect('coc/create/' . $scope)->with('warning', 'File yang diupload bukan berekstensi PDF.');
            }
        }
//        $size = $file->getSize();
//        if($size>1000000){
//            return  redirect('coc/create/')->with('warning', 'Ukuran file yang diupload melebihi 1MB');
//        }

//        dd($request);

//        $coc = Coc::findOrFail($id);

        $tanggal_coc = Carbon::parse($request->tanggal_coc . ' ' . $request->jam);

        // cek tema pada tanggal tsb
        $thematic = TemaCoc::where('start_date', '<=', $tanggal_coc)
            ->where('end_date', '>=', $tanggal_coc)
            ->first();

//        dd($thematic);

        // jika belum ada tema
        if ($thematic == null)
            return redirect('coc')->with('warning', 'Tema belum tersedia. Mohon tunggu sampai ada tema dari Kantor Pusat.');

        if ($scope == 'nasional') {
            $lokasi = 'Kantor Pusat';
            $all_day = '1';
            $bg = 'bg-danger';
        } elseif ($scope == 'unit') {
            $lokasi = Auth::user()->companyCode->description;
            $all_day = '1';
            $bg = 'bg-warning';
        } elseif ($scope == 'local') {
            $lokasi = $request->lokasi;
            $all_day = '0';
            $bg = 'bg-primary';
        }

        // jika sudah ada tema
        $event = new Event();
        $event->title = $request->judul;
        $event->all_day = $all_day;
        $event->start = $tanggal_coc;
//        $event->end       = $tanggal_coc;
//        $event->url       = '';
//    $event->color   = '#1bb99a';
        $event->class_name = $bg;

        $event->save();


        $coc = new Coc();
        $coc->event_id = $event->id;
        $coc->tema_id = $thematic->tema->id;
//        $coc->pemateri_id   = $request->pemateri_id;
        $coc->admin_id = Auth::user()->id;
        $coc->tanggal_jam = $tanggal_coc;
        $coc->judul = $request->judul;
        $coc->deskripsi = $request->deskripsi;
        $coc->pernr_pemateri = $request->pernr_penulis;
        $coc->lokasi = $lokasi;

//        $pemateri = StrukturJabatan::where('pernr', $coc->pernr_pemateri)->first();
//        dd($pemateri->strukturOrganisasi->getOrgLevel());

        if ($scope == 'local') {
            $coc->company_code = Auth::user()->company_code;
            $coc->business_area = Auth::user()->business_area;
            $coc->orgeh = Auth::user()->getOrgLevel()->objid;

        } elseif ($scope == 'unit') {
            $coc->company_code = Auth::user()->company_code;
            $gsber = substr($coc->company_code, 0, 2) . '01';
            $coc->business_area = $gsber;
            $coc->orgeh = $coc->companyCode->hrp1008()->where('gsber', $gsber)->where('endda', '99991231')->first()->objid;
        } elseif ($scope == 'unit') {
            $coc->company_code = Auth::user()->company_code;
            $gsber = substr($coc->company_code, 0, 2) . '01';
            $coc->business_area = $gsber;
            $coc->orgeh = $coc->companyCode->hrp1008()->where('gsber', $gsber)->where('endda', '99991231')->first()->objid;
        }

//        $coc->company_code  = @$pemateri->strukturOrganisasi->getOrgLevel()->hrp1008->bukrs;
//        $coc->business_area = @$pemateri->strukturOrganisasi->getOrgLevel()->hrp1008->gsber;
//        $coc->orgeh         = @$pemateri->strukturOrganisasi->getOrgLevel()->objid;

        $coc->scope = $scope;

        $coc->save();

        if ($file != null) {
            $attachment = new Attachment();
            $attachment->coc_id = $coc->id;
            $attachment->judul = $coc->judul;
            $attachment->filename = $file->getClientOriginalName();
            $attachment->save();

            Storage::put($coc->business_area . '/attachment/' . $file->getClientOriginalName(), File::get($file));
        }

        return redirect('coc/event/' . $coc->id)->with('success', 'CoC berhasil disimpan.');
    }

    public function storeMateri(MateriRequest $request)
    {

        if ($request->is('coc/create/local')) {
            $scope = 'local';
        } elseif ($request->is('coc/create/materi/gm')) {
            $scope = 'unit';
        } elseif ($request->is('coc/create/materi/nasional')) {
            $scope = 'nasional';
        } else {
            return redirect('coc')->with('error', 'URI not found.');
        }

        if ($request->pernr_penulis == '' && $request->jenis_materi != '2') {
            return redirect()->back()->withErrors('Penulis materi wajib diisi')->withInput();
        }

        $materi_existing = Materi::whereDate('tanggal', '=', Date::parse($request->tanggal_coc)->format('Y-m-d'))->where('company_code', Auth::user()->company_code)->first();

        if ($materi_existing != null) {
            Date::setLocale('id');
            return redirect('coc')->with('warning', 'Sudah ada materi pada tanggal ' . Date::parse($request->tanggal_coc)->format('d F Y'));
        }
        $file = $request->file('materi');
        if ($file != null) {
            $extension = strtolower($file->getClientOriginalExtension());
            if ($extension != 'pdf') {
                return redirect('coc/create/' . $scope)->with('warning', 'File yang diupload bukan berekstensi PDF.');
            }
        }
        $tanggal_coc = Carbon::parse($request->tanggal_coc);

        // cek tema pada tanggal tsb
        $thematic = TemaCoc::where('start_date', '<=', $tanggal_coc)
            ->where('end_date', '>=', $tanggal_coc)
            ->first();

        // jika belum ada tema
        if ($thematic == null)
            return redirect('coc')->with('warning', 'Tema belum tersedia. Mohon tunggu sampai ada tema dari Kantor Pusat.');

        if ($scope == 'nasional') {
            $lokasi = 'Kantor Pusat';
            $all_day = '1';
            $bg = 'bg-pink';
            $jenis_materi_id = '1';
        } elseif ($scope == 'unit') {
            $lokasi = Auth::user()->companyCode->description;
            $all_day = '1';
            $bg = 'bg-purple';
            $jenis_materi_id = '2';
        } elseif ($scope == 'local') {
            $lokasi = $request->lokasi;
            $all_day = '0';
            $bg = 'bg-primary';
            $jenis_materi_id = '3';
        }

        // jika sudah ada tema
        $event = new Event();
        $event->title = $request->judul;
        $event->all_day = $all_day;
        $event->start = $tanggal_coc;
        $event->class_name = $bg;

        $event->save();

        // create materi
        $materi = new Materi();
        $materi->event_id = $event->id;
        $materi->tema_id = $thematic->tema_id;
        $materi->pernr_penulis = $request->pernr_penulis;
        $materi->judul = $request->judul;
        $materi->deskripsi = $request->deskripsi;
        $materi->jenis_materi_id = $jenis_materi_id;

        $materi->company_code = Auth::user()->company_code;
        $materi->business_area = Auth::user()->business_area;
        $materi->orgeh = Auth::user()->getOrgLevel()->objid;

        $materi->tanggal = $tanggal_coc;

        // $materi->energize_day = $request->energize_day;

        if($request->jenis_materi=='2') $materi->energize_day = 1;
        if($request->jenis_materi=='3') $materi->rubrik_transformasi = 1;

    //    dd($materi);

        $materi->save();

        if ($file != null) {
            $attachment = new AttachmentMateri();
            $attachment->materi_id = $materi->id;
            $attachment->judul = $materi->judul;
            $attachment->filename = $file->getClientOriginalName();
            $attachment->save();

            Storage::put($materi->business_area . '/attachment_materi/' . $file->getClientOriginalName(), File::get($file));
        }

        if ($jenis_materi_id == '1') $txt_jenis = 'Nasional';
        elseif ($jenis_materi_id == '2') $txt_jenis = 'GM';
        else $txt_jenis = '';
        Activity::log('Create Materi ' . $txt_jenis . ' : ' . $materi->judul . ' (' . $materi->tanggal->format('d/m/Y') . '); ID: ' . $materi->id, 'success');

        // notifikasi ke para admin CoC

        $judul = $materi->judul;
        $tanggal = $materi->tanggal->format('d-m-Y');
        $id_materi = $materi->id;
        $tema = $materi->tema->tema;
        $jenis_materi = $materi->jenisMateri->jenis;
        $penulis = @$materi->penulis->cname;
        $jabatan_penulis = @$materi->penulis->strukturPosisi->stext;
        $energize_day = $materi->energize_day;
        $rubrik_transformasi = $materi->rubrik_transformasi;

        $role_admin = Role::find(4);
        if ($materi->jenis_materi_id == '1') {
            $admin_list = $role_admin->users;
            $color = 'pink';
        } else {
            $admin_list = $role_admin->users()->where('company_code', $materi->company_code)->get();
            $color = 'purple';
        }
        foreach ($admin_list as $user) {

            $notif = new Notification();
            $notif->from = Auth::user()->username2;
            $notif->to = $user->username2;
            $notif->user_id_from = Auth::user()->id;
            $notif->user_id_to = $user->id;
            $notif->subject = 'Materi ' . $jenis_materi . ' Telah Terbit';
            $notif->color = $color;
            $notif->icon = 'fa fa-book';

            $notif->message = $judul . '. Silakan membuat jadwal CoC untuk materi tersebut.';
            $notif->url = 'coc/initial/' . $id_materi;

            $notif->save();

            if($user->email!=''){
                $mail = new MailLog();
                $mail->to = $user->email;
                $mail->to_name = $user->name;
                $mail->subject = '[KOMANDO] Materi ' . $jenis_materi . ' Telah Diterbitkan';
                $mail->file_view = 'emails.materi_created';
                $mail->message = 'Materi ' . $jenis_materi . ' dengan judul ' . $judul . ' sudah dibuat untuk tanggal ' . $tanggal . '. Silakan membuat jadwal CoC untuk materi tersebut.';
                $mail->status = 'CRTD';
                $mail->parameter = '{"tema":"' . $tema . '","jenis":"' . $jenis_materi . '","judul":"' . $judul . '","penulis":"' . $penulis . '","jabatan_penulis":"' . $jabatan_penulis . '","tanggal":"' . $tanggal . '","energize_day":"' . $energize_day . '","rubrik_transformasi":"' . $rubrik_transformasi . '"}';
                $mail->notification_id = $notif->id;
                $mail->jenis = '2';

                $mail->save();
            }
        }

        return redirect('coc')->with('success', 'Materi berhasil disimpan.');
    }

//    public function storeNasional(Request $request)
//    {
//        $scope = 'nasional';
//
//        $file = $request->file('materi');
////        if ($file == null) {
////            return redirect('coc/create/')->with('warning', 'File materi belum dipilih');
////        }
//        if ($file != null) {
//            $extension = strtolower($file->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('coc/create/'.$scope)->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }
//        }
////        $size = $file->getSize();
////        if($size>1000000){
////            return  redirect('coc/create/')->with('warning', 'Ukuran file yang diupload melebihi 1MB');
////        }
//
////        dd($request);
//
////        $coc = Coc::findOrFail($id);
//
//            $tanggal_coc = Carbon::parse($request->tanggal_coc . ' ' . $request->jam);
//
//            // cek tema pada tanggal tsb
//            $thematic = TemaCoc::where('start_date', '<=', $tanggal_coc)
//                ->where('end_date', '>=', $tanggal_coc)
//                ->first();
//
//            // jika belum ada tema
//            if ($thematic == null)
//                return redirect('coc')->with('warning', 'Tema belum tersedia. Mohon tunggu sampai ada tema dari Kantor Pusat.');
//
//        foreach(StrukturOrganisasi::whereIn('level', [1,2,3])->get() as $org) {
//            // jika sudah ada tema
//            $event = new Event();
//            $event->title = $request->topik;
//            $event->all_day = '1';
//            $event->start = $tanggal_coc;
////        $event->end       = $tanggal_coc;
////        $event->url       = '';
////    $event->color   = '#1bb99a';
//            $event->class_name = 'bg-danger';
//
//            $event->save();
//
//            $coc = new Coc();
//            $coc->event_id = $event->id;
//            $coc->tema_id = $thematic->tema->id;
////        $coc->pemateri_id   = $request->pemateri_id;
//            $coc->admin_id = Auth::user()->id;
//            $coc->tanggal_jam = $tanggal_coc;
//            $coc->judul = $request->topik;
//            $coc->deskripsi = $request->deskripsi;
////            $coc->pernr_pemateri = $request->pernr_pemateri;
////            $coc->lokasi = $request->lokasi;
//
////        $pemateri = StrukturJabatan::where('pernr', $coc->pernr_pemateri)->first();
////        dd($pemateri->strukturOrganisasi->getOrgLevel());
//
//
//            if($org->hrp1008!=null){
//
//                $business_area = BusinessArea::where('business_area',$org->hrp1008->gsber)->first();
//
//                $coc->business_area = @$business_area->business_area;
//                $coc->company_code = @$business_area->company_code;
//            }
//            elseif($org->parent->hrp1008!=null){
//                $business_area = BusinessArea::where('business_area',$org->parent->hrp1008->gsber)->first();
//                $coc->business_area = @$business_area->business_area;
//                $coc->company_code = @$business_area->company_code;
//            }
//            elseif($org->parent->parent->hrp1008!=null){
//                $business_area = BusinessArea::where('business_area',$org->parent->parent->hrp1008->gsber)->first();
//                $coc->business_area = @$business_area->business_area;
//                $coc->company_code = @$business_area->company_code;
//            }
//
//            $coc->orgeh = $org->objid;
//
//            $coc->scope = $scope;
//
//            $coc->save();
//
//            if($org->level==1 || $org->level==2) {
//                if ($file != null) {
//                    $attachment = new Attachment();
//                    $attachment->coc_id = $coc->id;
//                    $attachment->judul = $coc->judul;
//                    $attachment->filename = $file->getClientOriginalName();
//                    $attachment->save();
//
//                    Storage::put($coc->business_area . '/attachment/' . $file->getClientOriginalName(), File::get($file));
//                }
//            }
//        }
//
//        return redirect('coc')->with('success', 'CoC berhasil disimpan.');
//    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $request = request();
        $coc = Coc::findOrFail($id);

        $search = $request->search;

        // cari organisasi2 di bawah orgeh coc
        $coc_orgeh = StrukturOrganisasi::where('objid', $coc->orgeh)->first();
        $arr_orgeh = $coc_orgeh->getChildren();

        $query = User::whereIn('orgeh', $arr_orgeh)
            // ->where('nip','84115250X')
            ->where('status', 'ACTV')
            ->where(function ($query) use ($search) {
                return $query->when(! empty($search), function ($query) use ($search) {
                    $keyword = strtolower($search['value']);
                    return $query->where(function ($query) use ($keyword) {
                        return $query->where('lower(name)', 'like', "%$keyword%")
                            ->orWhere('nip', 'like', "%$keyword%")
                            ->orWhere('bidang', 'like', "%$keyword%")
                            ->orWhere('jabatan', 'like', "%$keyword%")
                            ->orWhere('business_area', 'like', "%$keyword%");
                    });
                });
            });

        // dd($query->take(10)->get());

        if($coc->materi!=null){
        $datatable = Datatable::make($query)
            ->rowView('components.datatable-columns.peserta', compact('coc'))
            ->columns([
                ['data' => 'avatar', 'searchable' => false, 'orderable' => false],
                ['data' => 'nip', 'searchable' => false],
                ['data' => 'name', 'searchable' => false],
                ['data' => 'business_area', 'searchable' => false],
                ['data' => 'bidang', 'searchable' => false],
                ['data' => 'jabatan', 'searchable' => false],
                ['data' => 'status', 'searchable' => false],
                ['data' => 'checkin', 'searchable' => false],
                ['data' => 'baca_materi', 'searchable' => false],
            ]);
        }
        else{
            $datatable = Datatable::make($query)
            ->rowView('components.datatable-columns.peserta_local', compact('coc'))
            ->columns([
                ['data' => 'avatar', 'searchable' => false, 'orderable' => false],
                ['data' => 'nip', 'searchable' => false],
                ['data' => 'name', 'searchable' => false],
                ['data' => 'business_area', 'searchable' => false],
                ['data' => 'bidang', 'searchable' => false],
                ['data' => 'jabatan', 'searchable' => false],
                ['data' => 'status', 'searchable' => false],
                ['data' => 'checkin', 'searchable' => false],
            ]);
        }

        // dd(request()->wantsJson());

        if (request()->wantsJson()) {
            return $datatable->toJson();
        }

        $jml_pegawai = $coc->jml_peserta;

        // ambil tema
        $tema_list[] = 'Select Tema';
        foreach (Tema::all()->sortBy('id') as $wa) {
            $tema_list[$wa->id] = $wa->tema;
        }

        // ambil jenjang jabatan
        $jenjang_list[] = 'Select Jenjang';
        foreach (JenjangJabatan::all()->sortBy('id') as $wa) {
            $jenjang_list[$wa->id] = $wa->jenjang_jabatan;
        }

        // ambil business area
        $busa_list[] = 'Select Unit';
        foreach (BusinessArea::all()->sortBy('id') as $wa) {
            $busa_list[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
        }

        $swal2 = true;

        // get id attendants
        // $arr_attendats = $coc->attendants->pluck('user_id')->toArray();

        // cari organisasi2 di bawah orgeh coc
        // $coc_orgeh = StrukturOrganisasi::where('objid', $coc->orgeh)->first();
        // $arr_orgeh = $coc_orgeh->getChildren();
       
        // cari nip dari organisasi2 di baawah orgeh coc
        // $arr_nip = StrukturJabatan::whereIn('orgeh', $arr_orgeh)->pluck('nip')->toArray();
        // $arr_nip = User::whereIn('orgeh', $arr_orgeh)->pluck('nip')->toArray();

        // cari user berdasarkan NIP
        // $absence_list = User::where('status', 'ACTV')
        //     ->whereIn('nip', $arr_nip)
        //     ->whereNotIn('id', $arr_attendats)
        //     ->get();

        $absence_list=array();

        return view('coc.coc_display', compact('coc', 'jml_pegawai', 'tema_list', 'jenjang_list', 'busa_list', 'swal2', 'absence_list', 'datatable'));
    }

    public function complete(CompleteRequest $request, $id)
    {

//dd($request);
        $realisasi = new RealisasiCoc();
        $realisasi->coc_id = $id;
//dd($realisasi);
        $leader = StrukturJabatan::where('pernr', $request->pernr_leader)->first();
//dd($leader);
        $jenjang = $leader->getJenjangJabatan();
//dd($leader->pa0001()->where('mandt', '100')->where('endda', '99991231')->first());
        $pa0001 = $leader->pa0001()->where('mandt', '100')->where('endda', '99991231')->first();

//dd($pa0001);

        $realisasi->level = $leader->getLevelUnit();
        $realisasi->jenjang_id = $jenjang->id;
        $realisasi->pernr_leader = $request->pernr_leader;
        $realisasi->business_area = $pa0001->gsber;
//        $business_area = BusinessArea::where('business_area', $gsber)->first();
        $realisasi->company_code = $pa0001->bukrs;
        $realisasi->realisasi = Carbon::parse($request->tanggal_coc . ' ' . $request->jam_coc);

        //      dd($realisasi);

        $realisasi->save();

        $coc = Coc::findOrFail($id);
        $coc->status = 'COMP';
        $coc->save();

        return redirect('coc/event/' . $id)->with('info', 'Realisasi CoC berhasil disimpan.');
    }

    public function completeAdmin(CompleteRequest $request)
    {

        $id = $request->coc_id;
        if ($id == null || $id == '') {
            return redirect('coc/list/admin')->withErrors('ID CoC tidak ditemukan.');
        }

        $coc = Coc::findOrFail($id);
        if ($request->jml_peserta_dispensasi == $coc->jml_peserta ) {
            return redirect('coc/list/admin')->withErrors('Jumlah dispensasi peserta CoC tidak boleh sama dengan jumlah peserta CoC.');
        }

        if ($request->jml_peserta_dispensasi > $coc->jml_peserta ) {
            return redirect('coc/list/admin')->withErrors('Jumlah dispensasi peserta CoC tidak boleh melebihi jumlah peserta CoC.');
        }

        $attendant = $coc->attendants->count();
        if($attendant == 0){
            return redirect()->back()->with('error', 'Belum ada peserta yang check-in.');
        }

        // get pernr leader
        $pernr_leader = PA0032::where('nip', $request->nip_leader)->first();
        // if pernr leader found
        if ($pernr_leader != null) {
            $pernr_leader = $pernr_leader->pernr;
        } else {
            $pernr_leader = '';
        }

         // holding
         if(!($coc->company_code == '1200' || $coc->company_code == '1300')){
            $leader = StrukturJabatan::where('pernr', $pernr_leader)->first();
            $jenjang = $leader->getJenjangJabatan();
         
            if($jenjang == null){
                return redirect()->back()->with('error', 'Jenjang leader tidak ditemukan di sistem. Silakan hubungi Administrator Pusat.');
            }
            $pa0001 = $leader->pa0001()->where('mandt', '100')->orderBy('endda', 'desc')->take(1)->get()->first();

            // cek apakah unit leader masih sama atau sudah mutasi
            if($pa0001->gsber != $coc->business_area){
                return redirect()->back()->with('warning', 'Leader sudah mutasi atau unit leader berbeda dengan unit room CoC. Silakan pilih leader lain dengan jabatan yang sesuai.');
            }

            $level = $leader->getLevelUnit();
            $jenjang_id = $jenjang->id;
            $plans_leader = @$leader->getDefinitive();
            $delegation = $leader->plans;

            $business_area = $pa0001->gsber;
            $company_code = $pa0001->bukrs;
        }
        else{
            $level = '1';
            $jenjang_id = '1';
            $user_leader = User::where('nip', $request->nip_leader)->first();
            if($user_leader){
                $plans_leader = $user_leader->plans;
                $delegation = $user_leader->plans;
            }
            else{
                $plans_leader = '';
                $delegation = '';
            }

            $business_area = $coc->business_area;
            $company_code = $coc->company_code;

        }

        $realisasi = new RealisasiCoc();
        $realisasi->coc_id = $id;

        $realisasi->level = $level;
        $realisasi->jenjang_id = $jenjang_id;

        $realisasi->pernr_leader = $pernr_leader;
        $realisasi->nip_leader = $request->nip_leader;
        $realisasi->business_area = $business_area;
        $realisasi->company_code = $company_code;
        $realisasi->realisasi = Carbon::parse($request->tanggal_coc . ' ' . $request->jam_coc);

        $realisasi->orgeh = $coc->orgeh;
        $realisasi->plans = $plans_leader;
        $realisasi->delegation = $delegation;

        $realisasi->jml_peserta_dispensasi = $request->jml_peserta_dispensasi;

        $realisasi->save();

        $coc->status = 'COMP';
        $coc->jml_peserta_dispensasi = $request->jml_peserta_dispensasi;
        $coc->save();

        Activity::log('Complete CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, 'success');

        return redirect('coc/list/admin')->with('info', 'Realisasi CoC berhasil disimpan.');
    }

    public function cancelCoc($id)
    {
        $coc = Coc::find($id);
        $coc->status = 'CANC';
        $coc->save();

        // delete ritual
        $ritual = RitualCoc::where('admin_id', $coc->admin_id)
            ->where('orgeh', $coc->orgeh)
            ->where('coc_id', $coc->id)
            ->where('pedoman_id', $coc->pedoman_perilaku_id)
            ->get();
        foreach ($ritual as $perilaku) {
            $perilaku->delete();
        }

        Activity::log('Cancel CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, 'success');

        /*
        // delete notif dan email peserta coc

        $notif_peserta = Notification::where('url', 'coc/check-in/' . $coc->id)->get();
        foreach ($notif_peserta as $notif) {
            $mail = MailLog::where('notification_id', $notif->id)->first();
            $mail->delete();
            $notif->url = 'coc';
            $notif->save();
        }
        */

        return redirect('coc')->with('success', 'Jadwal CoC berhasil dibatalkan.');
    }

    public function checkIn($id)
    {
        $coc = Coc::findOrFail($id);

        Activity::log('Initial Check-In CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, 'success');

        // cek apakan user masuk sebagai peserta atau bukan
        $coc_orgeh = StrukturOrganisasi::where('objid', $coc->orgeh)->first();
        $arr_orgeh = $coc_orgeh->getChildren();

        $orgeh_user = Auth::user()->orgeh;

        // cari user
        if(in_array($orgeh_user, $arr_orgeh)){
            $is_attendant = true;
        }
        else{
            $is_attendant = false;
        }

        $sudah_checkin = $coc->checkAtendant(Auth::user()->id);

        Date::setLocale('id');
        $tanggal_coc = Date::parse($coc->tanggal_jam);

        $now = Carbon::parse(date('Ymd'));
        $end = Carbon::parse($tanggal_coc->format('Ymd'));
        $selisih_hari = $end->diffInDays($now);

        return view('coc.coc_checkin_2', compact('coc', 'is_attendant', 'coc_orgeh', 'tanggal_coc', 'sudah_checkin', 'selisih_hari'));
    }

    public function checkInToday(Request $request)
    {

        // // get CoC hari ini
        $tanggal = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        // $tanggal = '2021-07-06';

        $arr_org_user = Auth::user()->getArrOrgLevel();

        // ambil coc hari ini

        // get id coc local dalam satu organisasi
        $coc = Coc::where('scope', 'local')
            ->whereIn('orgeh', $arr_org_user)
            ->whereDate('tanggal_jam', '=', $tanggal)
            ->where('jenis_coc_id',1)
            ->where('status', 'OPEN')
            ->first();

        if($coc!=null){

            // $coc = Coc::findOrFail($id);

            Activity::log('Initial Check-In CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, 'success');

    //        dd($coc);
            // $status_list = StatusCheckIn::all();

            // cek apakan user masuk sebagai peserta atau bukan
            $coc_orgeh = StrukturOrganisasi::where('objid', $coc->orgeh)->first();
            $arr_orgeh = $coc_orgeh->getChildren();

            // cari nip dari organisasi2 di baawah orgeh coc
            $arr_nip = StrukturJabatan::whereIn('orgeh', $arr_orgeh)->pluck('nip')->toArray();

            // get NIP user
            $nip_user = Auth::user()->nip;

            // cari user
            if(in_array($nip_user, $arr_nip)){
                $is_attendant = true;
            }
            else{
                $is_attendant = false;
            }

            $sudah_checkin = $coc->checkAtendant(Auth::user()->id);
            
            Date::setLocale('id');
            $tanggal_coc = Date::parse($coc->tanggal_jam);

            $now = Carbon::parse(date('Ymd'));
            $end = Carbon::parse($tanggal_coc->format('Ymd'));
            $selisih_hari = $end->diffInDays($now);

            return view('coc.coc_checkin_2', compact('coc', 'is_attendant', 'coc_orgeh', 'tanggal_coc', 'sudah_checkin', 'selisih_hari'));

        }
        else{
            return view('coc.no_coc_today', compact('coc', 'is_attendant', 'coc_orgeh', 'tanggal_coc', 'sudah_checkin', 'selisih_hari'));
        }
    }

    public function initialMateri($materi_id)
    {
        $materi = Materi::findOrFail($materi_id);
//        dd($materi);
        return view('coc.coc_initial', compact('materi'));
    }


    public function storeCheckIn(Request $request, $id)
    {
        $coc = Coc::findOrFail($id);

        if ($coc->checkAtendant(Auth::user()->id) == null) {
            $attendant = new Attendant();
            $attendant->coc_id = $coc->id;
            $attendant->user_id = Auth::user()->id;
            $attendant->check_in = Carbon::now();
            $attendant->status_checkin_id = $request->status_checkin;
            $attendant->save();
            $status = StatusCheckIn::find($attendant->status_checkin_id);
            Activity::log('Check-In CoC [' . @$status->status . '] : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, 'success');
        }

        return redirect('coc/event/' . $id)->with('info', 'Selamat datang. Anda terdaftar sebagai peserta CoC.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {

    }

    public function uploadFoto(Request $request, $id)
    {

        if ($request->judul == '' || $request->judul == null) {
            return redirect('coc/event/' . $id)->with('warning', 'Judul gambar wajib diisi');
        }

        $file = $request->file('foto');
        if ($file == null) {
            return redirect('coc/event/' . $id)->with('warning', 'File gambar belum dipilih');
        }
        $extension = strtolower($file->getClientOriginalExtension());
        if (!($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png')) {
            return redirect('coc/event/' . $id)->with('warning', 'File yang diupload bukan gambar');
        }
        $size = $file->getSize();
        if ($size > 3145728) {
            return redirect('coc/event/' . $id)->with('warning', 'Ukuran file yang diupload melebihi 3MB');
        }

        $coc = Coc::findOrFail($id);

        if ($coc->gallery()->where('status', 'ACTV')->count() == 3) {
            return redirect('coc/event/' . $id)->with('warning', 'Maksimal 3 foto untuk setiap CoC');
        }

        $coc->foto = $file->getClientOriginalName();
        $coc->save();

        $gallery = new GalleryCoc();
        $gallery->coc_id = $id;
        $gallery->folder = 'gallery/' . $coc->company_code;
        $gallery->filename = $id . '_' . $file->getClientOriginalName();
        $gallery->judul = $request->judul;
        $gallery->deskripsi = $request->deskripsi;
        $gallery->status = 'ACTV';
        $gallery->save();

        Storage::put($gallery->folder . '/' . $id . '_' . $file->getClientOriginalName(), File::get($file));

        $img = Image::make(File::get($file));
        $img->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        // save file as jpg with medium quality
        $img->save(storage_path('app/' . $gallery->folder . '/thumb/' . $id . '_' . $file->getClientOriginalName()));

        Activity::log('Upload Foto CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, 'success');

        return redirect('coc/event/' . $id)->with('success', 'Foto kegiatan berhasil disimpan.');

    }

    public function getFoto($id)
    {
//        $entry = UploadData::where('filename', '=', $filename)->firstOrFail();
        $coc = Coc::findOrFail($id);
        $file = Storage::get($coc->business_area . '/coc/' . $coc->foto);

        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="' . $coc->foto . '"');

    }

    public function storeComment(Request $request, $id)
    {
//        dd($request);
//        $coc = Coc::findOrFail($id);
        $comment = new Comment();
        $comment->parent_id = '0';
        $comment->coc_id = $id;
        $comment->user_id = Auth::user()->id;
        $comment->comment = $request->comment;
        $comment->save();

        return redirect('coc/event/' . $id);
    }

    public function visiMisi(Request $request, $coc_id)
    {
        $status_checkin = $request->status_checkin;
        $coc = Coc::find($coc_id);
        return view('coc.visi_misi', compact('coc', 'status_checkin'));
    }

    public function prinsip(Request $request, $coc_id)
    {
        $status_checkin = $request->status_checkin;
        $coc = Coc::find($coc_id);
        return view('coc.prinsip', compact('coc', 'status_checkin'));
    }

    public function nilai(Request $request, $coc_id)
    {
        $status_checkin = $request->status_checkin;
        $coc = Coc::find($coc_id);
        return view('coc.nilai_akhlak', compact('coc', 'status_checkin'));
    }

    public function tataNilai(Request $request, $coc_id)
    {
        $status_checkin = $request->status_checkin;
        $coc = Coc::find($coc_id);
        return view('coc.nilai_akhlak_2', compact('coc', 'status_checkin'));
    }

    public function fokusPerilaku(Request $request, $coc_id)
    {
        $status_checkin = $request->status_checkin;
        $coc = Coc::find($coc_id);
        return view('coc.fokus_perilaku_akhlak', compact('coc', 'status_checkin'));
    }

    public function pedomanPerilaku(Request $request, $coc_id)
    {
        $status_checkin = $request->status_checkin;
        $coc = Coc::findOrFail($coc_id);
        $pedoman = $coc->pedomanPerilaku;

        // get has DO or DON'T
        $arr_perilaku = array_flatten($coc->ritualCoc()->get(['perilaku_id'])->toArray());
        $perilaku_list = PerilakuDoDont::whereIn('id', $arr_perilaku)->get();
        $hasDo = false;
        $hasDont = false;
        foreach ($perilaku_list as $perilaku) {
            if ($perilaku->jenis == '1')
                $hasDo = true;
            if ($perilaku->jenis == '2')
                $hasDont = true;
        }

        return view('coc.pedoman_perilaku', compact('coc', 'pedoman', 'status_checkin', 'hasDo', 'hasDont'));
    }

    public function isuNasional(Request $request, $coc_id)
    {
        $status_checkin = $request->status_checkin;
        $coc = Coc::find($coc_id);

        // jika belum ada isu_nasional insert isu nasional
        $isu_nasional_coc = IsuNasionalCoC::where('coc_id', $coc_id)->first();

        if($isu_nasional_coc==null){
            $isu_nasional_coc = new IsuNasionalCoC();
            $isu_nasional_coc->admin_id = $coc->admin_id;
            $isu_nasional_coc->orgeh = $coc->orgeh;
            $isu_nasional_coc->coc_id = $coc->id;
            $isu_nasional_coc->isu_nasional_id = '5';
            $isu_nasional_coc->save();
        }

        $isu_nasional = $isu_nasional_coc->isuNasional;

        
        if($isu_nasional->jenis_isu_nasional_id==1){
            $delay = 100;
        }
        else{
            $count_word = count(explode(' ', $isu_nasional->description));
            $delay = ($count_word*8)/2;
        }
        return view('coc.isu_nasional', compact('coc', 'status_checkin', 'isu_nasional', 'count_word', 'delay'));
    }

    public function pelanggaran(Request $request, $coc_id)
    {
        $status_checkin = $request->status_checkin;
        $coc = Coc::find($coc_id);

        // jika belum ada pelanggaran insert pelanggaran
        $pelanggaran_coc = PelanggaranCoc::where('coc_id', $coc_id)->first();

        if($pelanggaran_coc==null){
            $pelanggaran_coc = new PelanggaranCoc();
            $pelanggaran_coc->admin_id = $coc->admin_id;
            $pelanggaran_coc->orgeh = $coc->orgeh;
            $pelanggaran_coc->coc_id = $coc->id;
            $pelanggaran_coc->jenis_pelanggaran_id = '1';
            $pelanggaran_coc->pelanggaran_id = '1';
            $pelanggaran_coc->status = 'ACTV';
            $pelanggaran_coc->save();
        }

//        $pelanggaran_coc = PelanggaranCoc::where('coc_id', $coc_id)->first();
        $pelanggaran = $pelanggaran_coc->pelanggaran;
        $jenis_pelanggaran = $pelanggaran->jenisPelanggaran;

        $count_word = count(explode(' ', $pelanggaran->description));
        return view('coc.pelanggaran', compact('coc', 'status_checkin', 'pelanggaran', 'jenis_pelanggaran', 'count_word'));
    }

    public function listCoc()
    {
        $user = Auth::user();
        $tgl = Date::now();
        $tgl_selected = $tgl->format('d-m-Y');

        $baUtil = new BusinessAreaUtil();
        $ukUtil = new UnitKerjaUtil();

        $bsSelected = $ukUtil->shiftingBusinessArea($user);
        $businessAreaOpts = $baUtil->generateOptions($user, $bsSelected);
        $businessAreaSelected = null;

        // get id coc unit
        $coc_unit = Coc::where('scope', 'local')
            ->whereIn('business_area', $bsSelected)
            ->whereDate('tanggal_jam', '=', $tgl->format('Y-m-d'))
            ->get(['id'])
            ->toArray();

        $coc_list = Coc::whereIn('id', $coc_unit)
            ->get();

        return view('coc.list_coc', compact(
            'businessAreaSelected', 'businessAreaOpts', 'tgl_selected', 'coc_list', 'user'
        ));
    }

    public function searchListCoc(Request $request)
    {
        $user = Auth::user();
        $tgl = Date::now();

        $baUtil = new BusinessAreaUtil();
        $ukUtil = new UnitKerjaUtil();

        if ($request->coc_date != null) {
            $tgl = Date::parse($request->coc_date);
        }

        $tgl_selected = $tgl->format('d-m-Y');
        $bsSelected = $ukUtil->shiftingBusinessArea($user);
        $businessAreaOpts = $baUtil->generateOptions($user, $bsSelected);
        $bsSelected = empty($request->business_area)
            ? $bsSelected
            : [$request->business_area];
        $businessAreaSelected = $request->business_area;

        // get id coc unit
        $coc_unit = Coc::where('scope', 'local')
            ->whereIn('business_area', $bsSelected)
            ->whereDate('tanggal_jam', '=', $tgl->format('Y-m-d'))
            ->get(['id'])
            ->toArray();

        $coc_list = Coc::whereIn('id', $coc_unit)
            ->get();

        return view('coc.list_coc', compact(
            'businessAreaSelected', 'businessAreaOpts', 'tgl_selected', 'coc_list', 'user'
        ));
    }

    public function listCocAdmin()
    {
        $user = Auth::user();
        $tgl = Date::now();
        $tgl_awal = Date::parse($tgl->format('d-m-Y'));
        $tgl_akhir = Date::parse($tgl->format('d-m-Y'));
        $baUtil = new BusinessAreaUtil();
        $ukUtil = new UnitKerjaUtil();

        $ba_selected = $ukUtil->shiftingBusinessArea($user);
        $bsAreaList = $baUtil->generateOptions($user, $ba_selected);

        // get id coc unit
        $coc_list = Coc::where('scope', 'local')
            ->whereIn('business_area', $ba_selected)
            // ->where('admin_id', $user->id)
            ->whereDate('tanggal_jam', '>=', $tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam', '<=', $tgl_akhir->format('Y-m-d'))
            ->get();

        return view('coc.list_coc_admin', compact(
            'ba_selected', 'bsAreaList', 'tgl_awal', 'tgl_akhir', 'coc_list'
        ));
    }

    public function searchListCocAdmin(Request $request)
    {
        $user = Auth::user();
        $tgl_awal = Date::parse($request->start_date);
        $tgl_akhir = Date::parse($request->end_date);
        $baUtil = new BusinessAreaUtil();
        $ukUtil = new UnitKerjaUtil();

        $bsSelected = $ukUtil->shiftingBusinessArea($user);
        $bsAreaList = $baUtil->generateOptions($user, $bsSelected);

        $businessAreaSelected = empty($request->business_area)
            ? $bsSelected
            : [$request->business_area];
        $ba_selected = $request->business_area;

        // get id coc unit
        $coc_list = Coc::where('scope', 'local')
            ->whereIn('business_area', $businessAreaSelected)
            // ->where('admin_id', $user->id)
            ->whereDate('tanggal_jam', '>=', $tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam', '<=', $tgl_akhir->format('Y-m-d'))
            ->get();

        return view('coc.list_coc_admin', compact(
            'ba_selected', 'bsAreaList', 'tgl_awal', 'tgl_akhir', 'coc_list'
        ));
    }

    public function ajaxGetPerilaku($id, $orgeh, $jenis)
    {
//        dd($orgeh);
//        $pedoman = PedomanPerilaku::find($id);
        $pedoman = DoDont::find($id);
        $list = $pedoman->perilaku()->where('jenis', $jenis)->get();
        $ritual = RitualCoc::where('admin_id', Auth::user()->id)->where('orgeh', $orgeh)->where('status', 'ACTV')->whereYear('created_at', '=', date('Y'))->get(['perilaku_id'])->toArray();
        $arr_perilaku = array_flatten($ritual);

        return view('coc.ajax_perilaku', compact('list', 'arr_perilaku'));
    }

    public function ajaxGetPelanggaran($orgeh)
    {
    //    dd($orgeh);

        $pelanggaran_coc = PelanggaranCoc::where('admin_id', Auth::user()->id)->where('orgeh', $orgeh)
                                ->where('status', 'ACTV')
                                ->whereYear('created_at', '=', date('Y'))
                                ->get(['pelanggaran_id'])
                                ->toArray();
        $arr_pelanggaran = array_flatten($pelanggaran_coc);

        $pelanggaran_list = Pelanggaran::whereNotIn('id', $arr_pelanggaran)->orderBy('id','asc')->get();

        // dd($pelanggaran_list);

        return view('coc.ajax_pelanggaran', compact('pelanggaran_list', 'arr_pelanggaran'));
    }

    public function ajaxGetHistoryPedoman($orgeh)
    {
//        $pedoman_list = PedomanPerilaku::all();
        $pedoman_list = DoDont::all();
        $organisasi = StrukturOrganisasi::where('objid', $orgeh)->first();
        $ritual = RitualCoc::where('admin_id', Auth::user()->id)->where('orgeh', $orgeh)->where('status', 'ACTV')->whereYear('created_at', '=', date('Y'))->get(['perilaku_id'])->toArray();
        $arr_perilaku = array_flatten($ritual);

//        dd($arr_perilaku);
        return view('coc.ajax_history', compact('organisasi', 'pedoman_list', 'arr_perilaku'));
    }

    public function ajaxGetHistoryPelanggaran($orgeh)
    {
        $organisasi = StrukturOrganisasi::where('objid', $orgeh)->first();
        $pelanggaran_coc = PelanggaranCoc::where('admin_id', Auth::user()->id)->where('orgeh', $orgeh)
            ->where('status', 'ACTV')
            ->whereYear('created_at', '=', date('Y'))
            ->get(['pelanggaran_id'])
            ->toArray();
        $arr_pelanggaran = array_flatten($pelanggaran_coc);

        $pelanggaran_history = Pelanggaran::whereIn('id', $arr_pelanggaran)->orderBy('id','asc')->get();

//        dd($arr_perilaku);
        return view('coc.ajax_history_pelanggaran', compact('organisasi', 'pelanggaran_history'));
    }

    public function ajaxClearHistoryPedoman($orgeh)
    {
        $ritual_list = RitualCoc::where('admin_id', Auth::user()->id)->where('orgeh', $orgeh)->where('status', 'ACTV')->whereYear('created_at', '=', date('Y'))->get();

        foreach ($ritual_list as $ritual) {
            $ritual->status = 'DEL';
            $ritual->save();
        }

        return 'OK';
//        $pedoman_list = PedomanPerilaku::all();
//        $organisasi = StrukturOrganisasi::where('objid', $orgeh)->first();
//        $ritual = RitualCoc::where('admin_id', Auth::user()->id)->where('orgeh', $orgeh)->whereYear('created_at','=',date('Y'))->get(['perilaku_id'])->toArray();
//        $arr_perilaku = array_flatten($ritual);
//
////        dd($arr_perilaku);
//        return view('coc.ajax_history', compact('organisasi','pedoman_list', 'arr_perilaku'));
    }

    public function ajaxClearHistoryPelanggaran($orgeh)
    {
        $pelanggaran_coc = PelanggaranCoc::where('admin_id', Auth::user()->id)->where('orgeh', $orgeh)->where('status', 'ACTV')->whereYear('created_at', '=', date('Y'))->get();

        foreach ($pelanggaran_coc as $pelanggaran) {
            $pelanggaran->status = 'DEL';
            $pelanggaran->save();
        }

        return 'OK';
    }

    public function ajaxGetCoc($id)
    {
        $coc = Coc::find($id);

//        return response()->json($coc);
        return $coc;
    }

    public function deleteFoto($foto_id)
    {
        $foto_coc = GalleryCoc::find($foto_id);
        $foto_coc->status = 'DEL';
        $foto_coc->save();

        $coc = Coc::find($foto_coc->coc_id);

        Activity::log('Delete Foto CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, 'success');

        return redirect('coc/event/' . $coc->id)->with('success', 'Foto kegiatan berhasil dihapus.');
    }

    public function initialOrgehRealisasi()
    {
        $realisasi = RealisasiCoc::all();

        foreach ($realisasi as $real) {
            $pernr = $real->pernr_leader;
            $pa0001 = PA0001::where('pernr', $pernr)->first();
            $real->orgeh = $pa0001->orgeh;
            $real->plans = $pa0001->plans;
//            dd($real);
            $real->save();
            echo $real->pernr_leader . '|' . $real->orgeh . '|' . $real->plans . '<br>';
        }

        return 'FINISH';

    }


    public function autoCompleteRoom(){
        $date_seminggu_lalu = Carbon::now()->subDays(5);
	//$date_seminggu_lalu = Carbon::createFromFormat('Y-m-d', '2020-02-29');

	// dd($date_seminggu_lalu);

        // get room CoC < H-7

        $list_coc_open = Coc::where('status','OPEN')->whereYear('tanggal_jam','=',date('Y'))->where('tanggal_jam','<=',$date_seminggu_lalu)->orderBy('id', 'asc')->get();

        // dd($list_coc_open);

        // complete CoC

        foreach ($list_coc_open as $data) {

//            $id = $request->coc_id;
//            if ($id == null || $id == '') {
//                return redirect('coc/list/admin')->with('error', 'ID CoC tidak ditemukan.');
//            }

//            $coc = Coc::findOrFail($id);
            $coc = $data;
            $pernr_leader = $coc->pernr_leader;
            $tanggal = $coc->tanggal_jam->format('Ymd');

            $leader = StrukturJabatan::where('pernr', $pernr_leader)->first();

            $jenjang = $leader->getJenjangJabatan();

            $coc->status = 'COMP';
            $coc->autocomplete = 1;
            $coc->save();

            if ($jenjang == null) {
//                return redirect()->back()->with('error', 'Jenjang leader tidak ditemukan di sistem. Silakan hubungi Administrator Pusat.');
                echo 'EAC-001 :'.$coc->id.'<br>';
                Autocomplete::log('ERROR', 'EAC001 : Jenjang leader tidak ditemukan di sistem.', $coc->id, $coc->tanggal_jam, 0);
                continue;
            }

            $pa0001 = $leader->pa0001()->where('mandt', '100')->orderBy('endda', 'desc')->take(1)->get()->first();

            // cek apakah unit leader masih sama atau sudah mutasi
            if ($pa0001->gsber != $coc->business_area) {
//                return redirect()->back()->with('warning', 'Leader sudah mutasi atau unit leader berbeda dengan unit room CoC. Silakan pilih leader lain dengan jabatan yang sesuai.');
                echo 'EAC-002 :'.$coc->id.'<br>';
                Autocomplete::log('ERROR', 'EAC002 : Leader sudah mutasi atau unit leader berbeda dengan unit room CoC.', $coc->id, $coc->tanggal_jam, 0);
                continue;
            }

            $realisasi = new RealisasiCoc();
            $realisasi->coc_id = $coc->id;

            $realisasi->level = $leader->getLevelUnit();
            $realisasi->jenjang_id = $jenjang->id;
            $realisasi->pernr_leader = $coc->pernr_leader;
            $realisasi->business_area = $pa0001->gsber;
            $realisasi->company_code = $pa0001->bukrs;
//            $realisasi->realisasi = Carbon::parse($request->tanggal_coc . ' ' . $request->jam_coc);
            $realisasi->realisasi = $coc->tanggal_jam;

            $realisasi->orgeh = $coc->orgeh;
            $realisasi->plans = @$leader->getDefinitive();
            $realisasi->delegation = $leader->plans;
            $realisasi->autocomplete = 1;

            $realisasi->save();

//            $coc->status = 'COMP';
//            $coc->save();

//            Activity::log('Complete CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, 'success');
            echo 'OK :'.$coc->id.'<br>';
            Autocomplete::log('SUCCESS', 'Complete CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, $coc->id, $coc->tanggal_jam, $realisasi->id);

//        return redirect('coc/list/admin')->with('info', 'Realisasi CoC berhasil disimpan.');

        }

        echo "FINISHED";
    }

    public function autoCompleteRoomCoc(){
        $date_seminggu_lalu = Carbon::now()->subDays(env('SUBDAYS_AUTOCOMPLETE',5))->format('Y-m-d');

        echo $date_seminggu_lalu.'<br>';
        // get room CoC < H-5

        $list_coc_open = Coc::where('status','OPEN')
            ->whereDate('tanggal_jam','<=',$date_seminggu_lalu)
            ->whereNotNull('nip_leader')
            ->orderBy('tanggal_jam', 'desc')
            // ->take(10)
            ->get();

        // complete CoC
        foreach ($list_coc_open as $data) {
            $coc = $data;
            $pernr_leader = $coc->pernr_leader;
            $tanggal = $coc->tanggal_jam->format('Ymd');

            $coc->status = 'COMP';
            $coc->autocomplete = 1;
            $coc->save();

            if($coc->plans_leader=='ERR'){
                echo 'EAC-001 :'.$coc->id.'<br>';
                Autocomplete::log('ERROR', 'EAC001 : Jenjang leader tidak ditemukan di sistem.', $coc->id, $coc->tanggal_jam, 0);
                continue;
            }

            $realisasi = new RealisasiCoc();
            $realisasi->coc_id = $coc->id;

            $realisasi->level = $coc->level_unit;
            $realisasi->jenjang_id = $coc->jenjang_id;

            $realisasi->pernr_leader = $coc->pernr_leader;
            $realisasi->nip_leader = $coc->nip_leader;

            $realisasi->business_area = $coc->business_area;
            $realisasi->company_code = $coc->company_code;
            $realisasi->realisasi = $coc->tanggal_jam;

            $realisasi->orgeh = $coc->orgeh;
            $realisasi->plans = $coc->plans_leader;
            $realisasi->delegation = $coc->delegation_leader;
            $realisasi->autocomplete = 1;

            $realisasi->save();

            echo 'OK :'.$coc->id.'<br>';
            Autocomplete::log('SUCCESS', 'Complete CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, $coc->id, $coc->tanggal_jam, $realisasi->id);

        }

        echo "FINISHED";
    }

    public function autoCompleteRoomTahun(){
        $tahun_lalu = 2019;

        $list_coc_open = Coc::where('status','OPEN')->whereYear('tanggal_jam','<=',$tahun_lalu)->orderBy('id', 'asc')->take(500)->get();

//        dd($list_coc_open);

        // complete CoC

        foreach ($list_coc_open as $data) {

//            $id = $request->coc_id;
//            if ($id == null || $id == '') {
//                return redirect('coc/list/admin')->with('error', 'ID CoC tidak ditemukan.');
//            }

//            $coc = Coc::findOrFail($id);
            $coc = $data;
            $pernr_leader = $coc->pernr_leader;
            $tanggal = $coc->tanggal_jam->format('Ymd');

            $leader = StrukturJabatan::where('pernr', $pernr_leader)->first();

            $jenjang = $leader->getJenjangJabatan();

            $coc->status = 'COMP';
            $coc->autocomplete = 1;
            $coc->save();

            if ($jenjang == null) {
//                return redirect()->back()->with('error', 'Jenjang leader tidak ditemukan di sistem. Silakan hubungi Administrator Pusat.');
                echo 'EAC-001 :'.$coc->id.'<br>';
                Autocomplete::log('ERROR', 'EAC001 : Jenjang leader tidak ditemukan di sistem.', $coc->id, $coc->tanggal_jam, 0);
                continue;
            }

            $pa0001 = $leader->pa0001()->where('mandt', '100')->orderBy('endda', 'desc')->take(1)->get()->first();

            // cek apakah unit leader masih sama atau sudah mutasi
            if ($pa0001->gsber != $coc->business_area) {
//                return redirect()->back()->with('warning', 'Leader sudah mutasi atau unit leader berbeda dengan unit room CoC. Silakan pilih leader lain dengan jabatan yang sesuai.');
                echo 'EAC-002 :'.$coc->id.'<br>';
                Autocomplete::log('ERROR', 'EAC002 : Leader sudah mutasi atau unit leader berbeda dengan unit room CoC.', $coc->id, $coc->tanggal_jam, 0);
                continue;
            }

            $realisasi = new RealisasiCoc();
            $realisasi->coc_id = $coc->id;

            $realisasi->level = $leader->getLevelUnit();
            $realisasi->jenjang_id = $jenjang->id;
            $realisasi->pernr_leader = $coc->pernr_leader;
            $realisasi->business_area = $pa0001->gsber;
            $realisasi->company_code = $pa0001->bukrs;
//            $realisasi->realisasi = Carbon::parse($request->tanggal_coc . ' ' . $request->jam_coc);
            $realisasi->realisasi = $coc->tanggal_jam;

            $realisasi->orgeh = $coc->orgeh;
            $realisasi->plans = @$leader->getDefinitive();
            $realisasi->delegation = $leader->plans;
            $realisasi->autocomplete = 1;

            $realisasi->save();

//            $coc->status = 'COMP';
//            $coc->save();

//            Activity::log('Complete CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, 'success');
            echo 'OK :'.$coc->id.'<br>';
            Autocomplete::log('SUCCESS', 'Complete CoC : ' . $coc->judul . ' (' . $coc->tanggal_jam->format('d/m/Y') . '); ID: ' . $coc->id, $coc->id, $coc->tanggal_jam, $realisasi->id);

//        return redirect('coc/list/admin')->with('info', 'Realisasi CoC berhasil disimpan.');

        }

        echo "FINISHED";
    }

    public function massUpdateJenisCoC(){
        $coc_list = CoC::whereNotNull('materi_id')->whereNull('jenis_coc_id')->orderBy('id', 'desc')->take(1000)->get();
//        dd($coc_list);

        foreach ($coc_list as $data){
            $materi = Materi::findOrFail($data->materi_id);
            if ($materi->jenis_materi_id == '1') {
                $jenis_coc_id = '1';
            } elseif ($materi->jenis_materi_id == '2') {
                $jenis_coc_id = '2';
            }
            else {
                $jenis_coc_id = '5';
            }

            $data->jenis_coc_id = $jenis_coc_id;
            $data->save();

            echo $jenis_coc_id.'<br>';
        }
    }

    public function ajaxGetJmlPegawai($orgeh){

            // cari organisasi2 di bawah orgeh coc
            $coc_orgeh = StrukturOrganisasi::where('objid', $orgeh)->first();
            
            $arr_orgeh = $coc_orgeh->getChildren();
            $jml_pegawai = 0;

            // Split the array into chunks of 1000 items
            $chunks = array_chunk($arr_orgeh, 1000);
            
            foreach ($chunks as $chunk) {
                // Execute the query for each chunk
                $jml_pegawai += User::where('status', 'ACTV')->whereIn('orgeh', $chunk)->pluck('nip')->count();
            }

            return $jml_pegawai;
    }

    public function massUpdatePlansLeaderCoc(){
        $coc_list = CoC::where('status', 'OPEN')->whereNull('plans_leader')->orderBy('id', 'asc')->take(1000)->get();
//        dd($coc_list);

        foreach ($coc_list as $data){

            // save plans + delegation leader for autocomplete
            $leader = StrukturJabatan::where('pernr', $data->pernr_pemateri)->first();
            $jenjang = $leader->getJenjangJabatan();

            if($jenjang == null){
//                return redirect()->back()->with('error', 'Jenjang leader tidak ditemukan di sistem. Silakan hubungi Administrator Pusat.');
                echo "ERR: Jenjang leader tidak ditemukan di sistem.<br>";
                $data->plans_leader = 'ERR';
                $data->save();
                continue;
            }

            $data->level_unit = $leader->getLevelUnit();
            $data->jenjang_id = $jenjang->id;
            $data->plans_leader = @$leader->getDefinitive();
            $data->delegation_leader = $leader->plans;
            $data->save();

            echo $data->plans_leader.' - ';
        }
    }

    public function massInsertPelanggaranCoc(){
        $search_pelanggaran = PelanggaranCoc::pluck('coc_id');
        $coc_list = CoC::where('status', 'OPEN')
                        ->whereNotIn('id', $search_pelanggaran)
                        ->orderBy('id', 'asc')
                        ->take(1000)
                        ->get();
//        dd($coc_list);
        foreach ($coc_list as $coc){
            $pelanggaran = new PelanggaranCoc();
            $pelanggaran->admin_id = $coc->admin_id;
            $pelanggaran->orgeh = $coc->orgeh;
            $pelanggaran->coc_id = $coc->id;
            $pelanggaran->jenis_pelanggaran_id = '1';
            $pelanggaran->pelanggaran_id = '1';
            $pelanggaran->status = 'ACTV';
            $pelanggaran->save();
            echo 'coc_id: '.$coc->id.' - ';
        }

        echo 'FINISH';
    }

    public function exportPeserta($id){

        $coc = Coc::findOrFail($id);
        // $jml_pegawai = $coc->jml_peserta;

        // get id attendants
        $arr_attendats = $coc->attendants->pluck('user_id')->toArray();

        // cari organisasi2 di bawah orgeh coc
        $coc_orgeh = StrukturOrganisasi::where('objid', $coc->orgeh)->first();
        $arr_orgeh = $coc_orgeh->getChildren();

        // cari nip dari organisasi2 di baawah orgeh coc
        // $arr_nip = StrukturJabatan::whereIn('orgeh', $arr_orgeh)->pluck('nip')->toArray();

        // cari user berdasarkan NIP
        $absence_list = User::where('status', 'ACTV')
            // ->whereIn('nip', $arr_nip)
            ->whereIn('orgeh', $arr_orgeh)
            ->whereNotIn('id', $arr_attendats)
            ->get();

        // dd($absence_list);

        Excel::create(date('YmdHis').'_peserta_coc_'.$coc->id,
            function ($excel) use ($coc, $absence_list) {

                $excel->sheet('Sudah Check-in', function ($sheet) use ($coc) {
                    $sheet->loadView('report/template_peserta_coc', [
                        'coc'=>$coc
                    ]);
                });

                $excel->sheet('Belum Check-in', function ($sheet) use ($coc, $absence_list) {
                    $sheet->loadView('report/template_peserta_coc_belum', [
                        'coc'=>$coc,
                        'absence_list'=>$absence_list
                    ]);
                });

            })->download('xlsx');

    }

    public function checkAbsence(Request $request){
        $personel_area = $request->get('personel_area');
        // '17100756';
        $coc_id = $request->get('coc_id');
        // '568882'

        $coc = Coc::find($coc_id);

        // get id attendants
        $arr_attendats = $coc->attendants->pluck('user_id')->toArray();

        // cari organisasi2 di bawah orgeh coc
        $coc_orgeh = StrukturOrganisasi::where('objid', $personel_area)->first();
        $arr_orgeh = $coc_orgeh->getChildren();

        // cari nip dari organisasi2 di baawah orgeh coc
        $arr_nip = StrukturJabatan::whereIn('orgeh', $arr_orgeh)->pluck('nip')->toArray();

        // cari user berdasarkan NIP
        $absence_list = User::where('status', 'ACTV')
            ->whereIn('nip', $arr_nip)
            ->whereNotIn('id', $arr_attendats)
            ->orderBy('name','asc')
            ->get(['id','nip','name','email'])->toArray();
        // dd($absence_list);
        

        return $absence_list;
    }

    public function checkAbsence1Level(Request $request){
        $personel_area = $request->get('personel_area');
        // '17100756';
        $coc_id = $request->get('coc_id');
        // '568882'

        $coc = Coc::find($coc_id);

        // get id attendants
        $arr_attendats = $coc->attendants->pluck('user_id')->toArray();

        // cari organisasi2 di bawah orgeh coc
        $coc_orgeh = StrukturOrganisasi::where('objid', $personel_area)->first();
        // $arr_orgeh = $coc_orgeh->getChildren1Level();
        $orgeh = $coc_orgeh->objid;

        // cari nip dari organisasi2 di bawah orgeh coc
        $arr_nip = StrukturJabatan::where('orgeh', $orgeh)->pluck('nip')->toArray();

        // cari user berdasarkan NIP
        $absence_list = User::where('status', 'ACTV')
            ->whereIn('nip', $arr_nip)
            ->whereNotIn('id', $arr_attendats)
            ->orderBy('name','asc')
            ->get(['id','nip','name','email'])->toArray();
        // dd($absence_list);


        return $absence_list;
    }

    public function getCocNasional(Request $request)
    {
        $kode_organisasi = $request->get('kode_organisasi');
        $tanggal = $request->get('tanggal');

        $coc = Coc::with('pemateri')->where('orgeh', $kode_organisasi)
                    ->whereDate('tanggal_jam', '=', $tanggal)
                    ->where('jenis_coc_id',1)
                    ->where('status', '!=', 'CANC')
                    ->first();

        return $coc;
    }

    public function sendNotifReportCoC(Request $request)
    {
        $kode_organisasi = $request->get('kode_organisasi');
        $chat_id = $request->get('chat_id');

        // send notification Report CoC
        $url_api = 'http://'.env('URL_BOT_KOMANDO').'/bot/send-notif-report-coc';
        $response = API::responsePost($url_api, ['kode_organisasi'=>$kode_organisasi,'chat_id'=>$chat_id]); 
        
        dd($response);
    }

    public function sendNotifReportCoCDIVSTI()
    {
        $kode_organisasi = '10073855'; // DIVSTI
        $chat_id = '1567317315'; // LOLOL
        // $chat_id = '-1001244409234'; // Grup DIVSTI

        // send notification Report CoC
        $url_api = 'http://'.env('URL_BOT_KOMANDO').'/bot/send-notif-report-coc';
        $response = API::responsePost($url_api, ['kode_organisasi'=>$kode_organisasi,'chat_id'=>$chat_id]); 

        $response = $response->getData();

        Bot::log($chat_id, 'OUT', 'Report CoC', $response->message, 'OK', $kode_organisasi);
        
        return $response->message;

    }

    public function reopen($id)
    {
        // check apakah $id memiliki karakter koma
        if(strpos($id, ',') !== false){
            $id = explode(',', $id);
            foreach ($id as $data) {
                $this->reopenCoc($data);
            }
        }
        else{
            $this->reopenCoc($id);
        }
        
        return '[OK] CoC berhasil dibuka kembali..';
    }

    public function reopenCoc($id)
    {
        // get data coc
        $coc = Coc::find($id); 

        if($coc==null){
            echo '[ERR] CoC ID: '.$id.' tidak ditemukan.<br>';

            return false;
        }

        // delete realisasi jika ada
        $realisasi = $coc->realisasi;
        // dd($realisasi);
        if($realisasi!=null)
        {
            $realisasi->delete();
            echo '[OK] Realisasi CoC ID: '.$id.' deleted.<br>';
        }

        // set status menjadi OPEN
        $coc->status = 'OPEN';
        $coc->save();

        echo '[OK] CoC ID: '.$id.' reopened.<br><br>';

        Activity::log('Reopen CoC ID: '.$id.'.', 'success');
    }
}
