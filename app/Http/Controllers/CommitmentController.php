<?php

namespace App\Http\Controllers;

//use Adldap\Laravel\Tests\Models\User;
use App\Activity;
use App\BusinessArea;
use App\Commitment;
use App\CompanyCode;
use App\Jawaban;
use App\JawabanPegawai;
use App\KomitmenPegawai;
use App\PA0001;
use App\PA0032;
use App\PedomanPerilaku;
use App\PerilakuPegawai;
use App\Pertanyaan;
use App\PLNTerbaik;
use App\Role;
use App\Services\Datatable;
use App\User;
use App\Utils\BusinessAreaUtil;
use App\Utils\CompanyCodeUtil;
use App\Utils\UnitKerjaUtil;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CommitmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $ba_selected = request('business_area');
        $baFromMultipleFirst = null;
        $baFromMultiple = (new UnitKerjaUtil)->shiftingBusinessArea($user);

        if (empty($ba_selected)) {
            $baFromMultipleFirst = $baFromMultiple[0];
        } else {
            $baFromMultipleFirst = $ba_selected;
        }

        $tahun = request('tahun', date('Y'));
        $ba_selected = $baFromMultipleFirst;
        $bsAreaList = (new BusinessAreaUtil)->generateOptions($user, $baFromMultiple, 'Unit');

        $business_area = BusinessArea::where('business_area', $baFromMultipleFirst)->first();

        $query = $business_area->users()
            ->with([
                'komitmenPegawai' => function ($query) use ($tahun) {
                    $query->where('tahun', $tahun);
                },
                'perilakuPegawai' => function ($query) use ($tahun) {
                    $query->where('tahun', $tahun);
                },
                'strukturJabatan', 'strukturJabatan.strukturOrganisasi', 'strukturJabatan.strukturPosisi'
            ])
            ->where('status', 'ACTV')
            ->orderBy('name', request()->input('order.0.dir'));

        $datatable = Datatable::make($query)
            ->rowView('commitment.commitment_list_row', compact('tahun'))
            ->columns([
                ['data' => 'name', 'sortable' => true],
                ['data' => 'nip', 'sortable' => false],
                ['data' => 'jabatan', 'sortable' => false],
                ['data' => 'bidang', 'sortable' => false],
                ['data' => 'tahun', 'sortable' => false],
                ['data' => 'jawaban', 'sortable' => false],
                ['data' => 'progress', 'sortable' => false],
                ['data' => 'commitment', 'sortable' => false],
            ])->search(function ($builder, $keyword) {
                $keyword = strtolower($keyword);

                $builder->where(function ($q) use ($keyword) {
                    $q->where(DB::raw('lower(name)'), 'like', "%$keyword%")
                        ->orWhere(DB::raw('lower(nip)'), 'like', "%$keyword%")
                        ->orWhereHas('strukturJabatan', function ($q2) use ($keyword) {
                            $q2->whereHas('strukturPosisi', function ($q3) use ($keyword) {
                                $q3->where(DB::raw('lower(stext)'), 'like', "%$keyword%");
                            })
                            ->orWhereHas('strukturOrganisasi', function ($q3) use ($keyword) {
                                $q3->where(DB::raw('lower(stext)'), 'like', "%$keyword%");
                            });
                        });
                });
            });

        if (request()->wantsJson()) {
            return $datatable->toJson();
        }

        return view('commitment.commitment_list', compact(
            'bsAreaList', 'ba_selected', 'user_list', 'tahun', 'datatable', 'user'
        ));
    }

    public function indexInduk()
    {
        $user = Auth::user();
        $tahun = request('tahun', date('Y'));
        $cc_selected = request('company_code');
        $companyCode = [];

        if (empty($cc_selected)) {
            $companyCode = (new UnitKerjaUtil)->shiftingCompanyCode($user);
            $cc_selected = $companyCode[0];
        }

        $coCodeList = (new CompanyCodeUtil)->generateOptions($user, $companyCode);

        $query = PA0001::where('persg', '=', '1')
            ->join('pa0032', 'pa0032.pernr', '=', 'pa0001.pernr')
            ->with(
                'pa0032', 'pa0032.user', 'pa0032.user.komitmenPegawai', 'pa0032.user.perilakuPegawai',
                'jabatan', 'jabatan.strukturPosisi', 'businessArea'
            )
            ->where('bukrs', $cc_selected)
            ->orderBy('gsber', 'asc')
            ->orderBy('sname', request()->input('order.0.dir'));

        $columns = [
            ['data' => 'name', 'sortable' => true],
            ['data' => 'nip', 'sortable' => false],
            ['data' => 'jabatan', 'sortable' => false],
            ['data' => 'unit', 'sortable' => false],
        ];
        for ($y = $tahun - 1; $y <= $tahun; $y++) {
            $columns[] = ['data' => 'progress_' . $y, 'sortable' => false];
        }

        $columns[] = ['data' => 'commitment', 'sortable' => false];

        $datatable = Datatable::make($query)
            ->rowView('commitment.commitment_list_induk_row')
            ->columns($columns)
            ->search(function ($builder, $keyword) {
                $keyword = strtolower($keyword);
                $builder->where(function ($q) use ($keyword) {
                    $q->where(DB::raw('lower(sname)'), 'like', "%$keyword%")

                    ->orWhere(DB::raw('lower(nip)'), 'like', '%'.$keyword.'%')

                    ->orWhereHas('jabatan.strukturPosisi', function ($q2) use ($keyword) {
                        $q2->where(DB::raw('lower(stext)'), 'like', "%$keyword%");
                    })

                    ->orWhereHas('businessArea', function ($q2) use ($keyword) {
                        $q2->where(DB::raw('lower(description)'), 'like', "%$keyword%");
                    });
                });
            });

        if (request()->wantsJson()) {
            return $datatable->toJson();
        }

        return view('commitment.commitment_list_induk', compact(
            'coCodeList', 'cc_selected', 'tahun', 'datatable', 'user'
        ));
    }

    public function rekapInduk()
    {
        $tahun_awal = env('START_YEAR', 2018);
        $ccList = CompanyCode::where('status', 'ACTV')->get()->sortBy('id');
        $query = CompanyCode::where('status', 'ACTV');
        $columns = [
            ['data' => 'company_code', 'searchable' => true],
            ['data' => 'description', 'searchable' => true],
        ];
        for ($x = $tahun_awal; $x <= date('Y'); $x++) {
            $columns[] = ['data' => 'commitment_' . $x, 'searchable' => false, 'sortable' => false];
        }

        $arr_komitmen = array();
        foreach ($ccList as $cc) {
            $pernr_pegawai = PA0001::where('persg', '=', '1')->where('bukrs', $cc->company_code)->orderBy('gsber', 'asc')->orderBy('sname', 'asc')->pluck('pernr')->toArray();
            $nip_pegawai = PA0032::whereIn('pernr', $pernr_pegawai)->pluck('nip')->toArray();
            $id_pegawai = User::whereIn('nip', $nip_pegawai)->pluck('id')->toArray();

            $arr_komit = array();
            for ($x = $tahun_awal; $x <= date('Y'); $x++) {
                $arr_komit[$x] = KomitmenPegawai::whereIn('user_id', $id_pegawai)->where('tahun', $x)->count();
            }
            $arr_komitmen[$cc->company_code] = $arr_komit;
        }

        $datatable = Datatable::make($query)->rowView('report.rekap_commitment_row', compact('arr_komitmen'))->columns($columns);

        if (request()->wantsJson()) {
            return $datatable->toJson();
        }

        return view('report.rekap_commitment', compact('ccList', 'arr_komitmen', 'datatable'));
    }

    public function exportRekapInduk()
    {
        $tahun_awal = env('START_YEAR', 2018);
        $ccList = CompanyCode::where('status', 'ACTV')->get()->sortBy('id');
        // dd($ccList);

        $arr_komitmen = array();
        // $arr_pegawai = array();
        foreach ($ccList as $cc) {
            $pernr_pegawai = PA0001::where('persg', '=', '1')->where('bukrs', $cc->company_code)->orderBy('gsber', 'asc')->orderBy('sname', 'asc')->pluck('pernr')->toArray();
            $nip_pegawai = PA0032::whereIn('pernr', $pernr_pegawai)->pluck('nip')->toArray();
            $id_pegawai = User::whereIn('nip', $nip_pegawai)->pluck('id')->toArray();

            $arr_komit = array();
            for ($x = $tahun_awal; $x <= date('Y'); $x++) {
                $komitmen = KomitmenPegawai::whereIn('user_id', $id_pegawai)->where('tahun', $x)->count();
                $arr_komit[$x] = $komitmen;
            }
            $arr_komitmen[$cc->company_code] = $arr_komit;
        }
        // dd($ccList);

        Excel::create(date('YmdHis') . '_rekap_komitmen', function ($excel) use ($ccList, $arr_komitmen) {

            $excel->sheet('Commitment', function ($sheet) use ($ccList, $arr_komitmen) {
                $sheet->loadView('commitment/template_rekap_komitmen')->with('ccList', $ccList)->with('arr_komitmen', $arr_komitmen);
            });
        })->download('xlsx');

        // return view('report.rekap_commitment', compact('ccList', 'arr_komitmen'));
    }

    public function searchResult(Request $request)
    {
        if ($request->business_area == 0)
            return redirect('commitment')->with('warning', 'Unit belum dipilih');

        $bsAreaList[0] = 'Select Unit';

        if (Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat')) $baList = BusinessArea::all()->sortBy('id');
        else $baList = BusinessArea::where('company_code', Auth::user()->company_code)->orderBy('id', 'asc')->get();

        foreach ($baList as $wa) {
            $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
        }
        $business_area = BusinessArea::where('business_area', $request->business_area)->first();
        $ba_selected = $business_area->business_area;
        $user_list = $business_area->users()->where('status', 'ACTV')->get();

        $tahun = $request->tahun;

        return view('commitment.commitment_list', compact('bsAreaList', 'ba_selected', 'user_list', 'tahun'));
    }

    public function searchResultInduk(Request $request)
    {
        if ($request->company_code == 0)
            return redirect('commitment-induk')->with('warning', 'Unit belum dipilih');

        //        $company_code = $request->company_code;

        $cc_selected = $request->company_code;
        $coCodeList[0] = 'Select Unit';

        if (Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat')) $ccList = CompanyCode::all()->sortBy('id');
        else $ccList = CompanyCode::where('company_code', Auth::user()->company_code)->orderBy('id', 'asc')->get();

        foreach ($ccList as $wa) {
            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }
        //        $company_code = CompanyCode::where('company_code', $cc_selected)->first();
        //        $user_list = $company_code->users()->where('status', 'ACTV')->get();

        //        dd($cc_selected);
        $pegawai_list = PA0001::where('persg', '=', '1')->where('bukrs', $cc_selected)->orderBy('gsber', 'asc')->orderBy('sname', 'asc')->get();
        //        dd($pegawai_list);
        $tahun = date('Y');

        return view('commitment.commitment_list_induk', compact('coCodeList', 'cc_selected', 'tahun', 'pegawai_list'));
    }

    public function pedomanPerilaku()
    {
        return view('commitment.pedoman_perilaku');
    }

    public function pertanyaan()
    {

        return view('commitment.pertanyaan');
    }

    public function komitmenPegawai($tahun)
    {
        if ($tahun > date('Y') || $tahun < 2017)
            return redirect('/')->with('warning', 'Tahun tidak sesuai');

        return view('commitment.komitmen_pegawai', compact('tahun'));
    }

    public function komitmenDireksi($tahun)
    {
        if ($tahun > date('Y') || $tahun < 2017)
            return redirect('/')->with('warning', 'Tahun tidak sesuai');

        return view('commitment.komitmen_direksi', compact('tahun'));
    }

    public function storeKomitmenDireksi(Request $request)
    {
        $tahun = $request->tahun;

        $user = User::where('id', Auth::user()->id)->first();

        // loop perilaku
        $perilaku_list = PLNTerbaik::all();

        foreach ($perilaku_list as $perilaku) {

            // save perilaku
            $perilaku_peg = new PerilakuPegawai();
            $perilaku_peg->user_id = $user->id;
            $perilaku_peg->pedoman_perilaku_id = $perilaku->id;
            $perilaku_peg->do = '1';
            $perilaku_peg->dont = '1';
            $perilaku_peg->tahun = $tahun;
            $perilaku_peg->save();

            // get pertanyaan perilaku
            $pertanyaan = Pertanyaan::where('pedoman_perilaku_id', $perilaku->id)->where('status', 'ACTV')->first();

            // get jawaban pertanyaan
            $jawaban = Jawaban::where('pertanyaan_id', $pertanyaan->id)->where('benar', '1')->where('status', 'ACTV')->first();

            // save jawaban
            $jawaban_pegawai                        = new JawabanPegawai();
            $jawaban_pegawai->user_id               = $user->id;
            if (Auth::user()->hasRole('direksi')) {
                $jawaban_pegawai->orgeh                 = $user->strukturJabatan->orgeh;
                $jawaban_pegawai->plans                 = $user->strukturJabatan->plans;
            }
            else{
                $jawaban_pegawai->orgeh                 = 0;
                $jawaban_pegawai->plans                 = 0;
            }
            $jawaban_pegawai->pedoman_perilaku_id   = $perilaku->id;
            $jawaban_pegawai->pertanyaan_id         = $pertanyaan->id;
            $jawaban_pegawai->jawaban_id            = $jawaban->id;
            $jawaban_pegawai->benar                 = '1';
            $jawaban_pegawai->tahun                 = $tahun;
            $jawaban_pegawai->save();
        }

        // insert komitmen
        // $jml_pedoman = env('JML_PEDOMAN', 14);

        $tahun = $tahun;

        // $jml_perilaku_pegawai = $user->perilakuPegawai()->where('tahun', $tahun)->get()->count();

        // if ($jml_perilaku_pegawai == $jml_pedoman) {
        $komitmen_pegawai = new KomitmenPegawai();
        $komitmen_pegawai->user_id = $user->id;
        if (Auth::user()->hasRole('direksi')) {
            $jabatan_pegawai = $user->strukturJabatan;
            $komitmen_pegawai->orgeh = $jabatan_pegawai->orgeh;
            $komitmen_pegawai->plans = $jabatan_pegawai->plans;
        }
        else{
            $komitmen_pegawai->orgeh = 0;
            $komitmen_pegawai->plans = 0;
        }
        $komitmen_pegawai->setuju = 1;
        $komitmen_pegawai->tahun = $tahun;
        $komitmen_pegawai->save();

        return redirect('/')->with('success', 'Komitmen Direksi / Komisaris ' . $tahun . ' berhasil disimpan. Terimakasih.');
        // } else {
        //     return redirect('/')->with('warning', 'Anda belum membaca semua Pedoman Perilaku');
        // }
    }

    public function storeKomitmenPegawai(Request $request)
    {
        $jml_pedoman = env('JML_PEDOMAN', 14);

        $tahun = $request->tahun;
        //        dd($tahun);

        $jml_perilaku_pegawai = Auth::user()->perilakuPegawai()->where('tahun', $tahun)->get()->count();

        if ($jml_perilaku_pegawai == $jml_pedoman) {
            $jabatan_pegawai = Auth::user()->strukturJabatan;
            $komitmen_pegawai = new KomitmenPegawai();
            $komitmen_pegawai->user_id = Auth::user()->id;
            $komitmen_pegawai->orgeh = $jabatan_pegawai->orgeh;
            $komitmen_pegawai->plans = $jabatan_pegawai->plans;
            $komitmen_pegawai->setuju = $request->setuju;
            $komitmen_pegawai->tahun = $tahun;
            $komitmen_pegawai->save();

            Activity::log('Tandatangan komitmen pegawai tahun ' . $tahun, 'success');

            return redirect('/')->with('success', 'Komitmen Pegawai ' . $tahun . ' berhasil disimpan. Terimakasih.');
        } else {
            return redirect('/')->with('warning', 'Anda belum membaca semua Pedoman Perilaku');
        }
    }

    public function exportCommitment($business_area)
    {
        ini_set('max_execution_time', 300);
        if ($business_area == 0)
            return redirect('commitment')->with('warning', 'Unit belum dipilih');

        $business_area = BusinessArea::where('business_area', $business_area)->first();
        $user_list = $business_area->users()->where('status', 'ACTV')->orderBy('name', 'asc')->get();

        Excel::create(date('YmdHis') . '_commitment_' . str_replace(' ', '_', strtolower($business_area->description)), function ($excel) use ($user_list, $business_area) {

            $excel->sheet('Commitment', function ($sheet) use ($user_list, $business_area) {
                $sheet->loadView('commitment/template_commitment')->with('user_list', $user_list)->with('business_area', $business_area);
            });
        })->download('xlsx');
    }

    public function exportCommitmentTahun($business_area, $tahun)
    {
        ini_set('max_execution_time', 300);
        if ($business_area == 0)
            return redirect('commitment')->with('warning', 'Unit belum dipilih');

        $business_area = BusinessArea::where('business_area', $business_area)->first();
        $user_list = $business_area->users()->where('status', 'ACTV')->orderBy('name', 'asc')->get();

        Excel::create(date('YmdHis') . '_commitment_' . str_replace(' ', '_', strtolower($business_area->description)) . '_' . $tahun, function ($excel) use ($user_list, $business_area, $tahun) {

            $excel->sheet('Commitment', function ($sheet) use ($user_list, $business_area, $tahun) {
                $sheet->loadView('commitment/template_commitment_tahun')
                    ->with('user_list', $user_list)
                    ->with('business_area', $business_area)
                    ->with('tahun', $tahun);
            });
        })->download('xlsx');
    }

    public function exportCommitmentDireksi($tahun)
    {
        ini_set('max_execution_time', 300);

        $role_direksi = Role::find(8);

        $user_list = $role_direksi->users()->where('status', 'ACTV')->orderBy('name', 'asc')->get();

        Excel::create(date('YmdHis') . '_commitment_direksi_' . $tahun, function ($excel) use ($user_list, $tahun) {

            $excel->sheet('Commitment', function ($sheet) use ($user_list, $tahun) {
                $sheet->loadView('commitment/template_commitment_direksi')
                    ->with('user_list', $user_list)
                    ->with('tahun', $tahun);
            });
        })->download('xlsx');
    }

    public function exportCommitmentDekom($tahun)
    {
        ini_set('max_execution_time', 300);

        $role_dekom = Role::find(12);

        $user_list = $role_dekom->users()->where('status', 'ACTV')->orderBy('name', 'asc')->get();

        Excel::create(date('YmdHis') . '_commitment_dekom_' . $tahun, function ($excel) use ($user_list, $tahun) {

            $excel->sheet('Commitment', function ($sheet) use ($user_list, $tahun) {
                $sheet->loadView('commitment/template_commitment_dekom')
                    ->with('user_list', $user_list)
                    ->with('tahun', $tahun);
            });
        })->download('xlsx');
    }

    public function exportCommitmentTahunInduk($company_code, $tahun)
    {
        ini_set('max_execution_time', 300);
        if ($company_code == 0)
            return redirect('commitment')->with('warning', 'Unit belum dipilih');
        $cc_selected = $company_code;
        $company_code = CompanyCode::where('company_code', $cc_selected)->first();
        //        $user_list = $company_code->users()->where('status', 'ACTV')->orderBy('name', 'asc')->get();
        $user_list = PA0001::where('persg', '=', '1')->where('bukrs', $cc_selected)->orderBy('gsber', 'asc')->orderBy('sname', 'asc')->get();

        Excel::create(date('YmdHis') . '_commitment_' . str_replace(' ', '_', strtolower($company_code->description)) . '_' . $tahun, function ($excel) use ($user_list, $company_code, $tahun) {

            $excel->sheet('Commitment', function ($sheet) use ($user_list, $company_code, $tahun) {
                $sheet->loadView('commitment/template_commitment_tahun_induk')
                    ->with('pegawai_list', $user_list)
                    ->with('company_code', $company_code)
                    ->with('tahun', $tahun);
            });
        })->download('xlsx');
    }

    public function exportCommitmentTahunAll($tahun)
    {
        ini_set('max_execution_time', 300);
        //        if($business_area==0)
        //            return redirect('commitment')->with('warning','Unit belum dipilih');

        //        $business_area = BusinessArea::where('business_area', $business_area)->first();
        $pegawai_list = PA0001::where('persg', '=', '1')->orderBy('gsber', 'asc')->orderBy('sname', 'asc')->get();
        //        $user_list = User::where('status', 'ACTV')->take(100)->orderBy('name','asc')->get();

        Excel::create(date('YmdHis') . '_commitment_' . $tahun, function ($excel) use ($pegawai_list, $tahun) {

            $excel->sheet('Commitment', function ($sheet) use ($pegawai_list, $tahun) {
                $sheet->loadView('commitment/template_commitment_tahun_all')
                    //                    ->with('user_list', $user_list)
                    ->with('pegawai_list', $pegawai_list)
                    ->with('tahun', $tahun);
            });
        })->download('xlsx');
    }

    public function commitmentPegawai()
    {
        //        $ba_selected = '';
        //        $bsAreaList[0] = 'Select Unit';
        //        foreach (BusinessArea::all()->sortBy('id') as $wa) {
        //            $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
        //        }

        // if (Auth::user()->cekBelumTandaTangan(date('Y'))) {
        //     return redirect('commitment/pedoman-perilaku/tahun/' . date('Y'));
        // }

        return view('commitment.commitment_list_pegawai');
    }

    public function deleteDuplicate()
    {
        //        $user = User::find(2594);

        $user_list = User::where('status', 'ACTV')->orderBy('id', 'asc')->get();

        foreach ($user_list as $user) {
            $perilaku = $user->perilakuPegawaiTahun(2017)->sortBy('pedoman_perilaku_id', 1);
            if ($perilaku->count() > 18) {
                echo '>>> ' . $perilaku . '<br>';
                for ($x = 1; $x <= 18; $x++) {
                    echo 'pedoman ' . $x . ' <br>';
                    $duplicate = $user->perilakuPegawaiTahun(2017)->where('pedoman_perilaku_id', $x . '')->sortBy('id', 1);
                    if ($duplicate->count() > 1) {
                        $y = 1;
                        foreach ($duplicate as $dup) {
                            echo $y . ' - ' . $dup->pedoman_perilaku_id . '<br>';
                            if ($y > 1) {
                                //                            dd($dup);
                                $dup->delete();
                                echo 'DEL<br>';
                            }
                            $y++;
                        }
                    }
                }
            }
        }
        dd($query);

    }

    public function getJmlKomitmenTahun($tahun)
    {
        $komitmen = KomitmenPegawai::where('tahun', $tahun)->get(['user_id']);
        //        $jml_komitmen = $komitmen->count();

        //        $unique = $komitmen->unique();

        //        dd($unique->values()->all());
        $arr = array_flatten($komitmen->toArray());
        $arr_remove_duplicate = array_unique($arr);
        //        dd(count($arr_remove_duplicate));
        return count($arr_remove_duplicate);
    }

    public function commitmentDireksi()
    {
        $tahun = request('tahun', date('Y'));

        $role_direksi = Role::find(8);

        $query = $role_direksi->users()
            ->with([
                'komitmenPegawai' => function ($query) use ($tahun) {
                    $query->where('tahun', $tahun);
                }
            ])
            ->where('status', 'ACTV')
            ->orderBy('name');

        $datatable = Datatable::make($query)
            ->rowView('commitment.commitment_list_direksi_row', compact('tahun'))
            ->columns([
                ['data' => 'name', 'sortable' => true, 'searchable' => true],
                ['data' => 'nip', 'sortable' => true, 'searchable' => true],
                ['data' => 'jabatan', 'sortable' => true],
                ['data' => 'tahun', 'sortable' => true],
                ['data' => 'commitment', 'sortable' => true],
            ]);

        if (request()->wantsJson()) {
            return $datatable->toJson();
        }

        return view('commitment.commitment_direksi', compact('user_list', 'tahun', 'datatable'));
    }

    public function commitmentDekom()
    {
        $tahun = request('tahun', date('Y'));

        $role_dekom = Role::find(12);

        $query = $role_dekom->users()
            ->with([
                'komitmenPegawai' => function ($query) use ($tahun) {
                    $query->where('tahun', $tahun);
                }
            ])
            ->where('status', 'ACTV')
            ->orderBy('name');

        $datatable = Datatable::make($query)
            ->rowView('commitment.commitment_list_dekom_row', compact('tahun'))
            ->columns([
                ['data' => 'name', 'sortable' => true, 'searchable' => true],
                ['data' => 'nip', 'sortable' => true, 'searchable' => true],
                ['data' => 'jabatan', 'sortable' => true],
                ['data' => 'tahun', 'sortable' => true],
                ['data' => 'commitment', 'sortable' => true],
            ]);

        if (request()->wantsJson()) {
            return $datatable->toJson();
        }

        return view('commitment.commitment_dekom', compact('user_list', 'tahun', 'datatable'));
    }
}
