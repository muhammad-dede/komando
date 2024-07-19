<?php

namespace App\Http\Controllers;

use App\CompanyCode;
use App\JenjangJabatan;
use App\TargetCoc;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StrukturOrganisasi;
use App\UnitMonitoring;

class TargetCocController extends Controller
{
    public function index()
    {
        $tahun_selected = date('Y');
        $tahunList[0] = 'Select Tahun';
        for($x=date('Y')-2;$x<=date('Y')+5;$x++) {
            $tahunList[$x] = $x;
        }
        
        $jenjang_list = JenjangJabatan::orderBy('id', 'asc')->get();
        
        return view('master.target_coc', compact('tahunList', 'tahun_selected', 'jenjang_list'));
    }

    public function targetCheckinCoc()
    {
        $unit_monitoring_list = UnitMonitoring::where('status','ACTV')->get();
        
        return view('master.target_checkin_coc', compact('unit_monitoring_list'));
    }

    public function createTargetCheckinCoc()
    {
        return view('master.create_target_checkin_coc');
    }

    public function submitTargetCheckinCoc(Request $request)
    {
        // cek kode organisasi
        $orgeh = StrukturOrganisasi::where('objid', $request->orgeh)->first();

        if($orgeh==null)
            return redirect()->back()->withInput()->with('warning', 'Kode organisasi tidak ditemukan.');

        // cek orgeh apakah sudah ada
        $unit_check = UnitMonitoring::where('orgeh', $request->orgeh)->where('status','ACTV')->first();
        if($unit_check!=null)
            return redirect()->back()->withInput()->with('warning', 'Kode organisasi sudah ada.');

        // cek company code
        $company_code = CompanyCode::where('company_code', $request->company_code)->first();
        if($company_code==null)
            return redirect()->back()->withInput()->with('warning', 'Company code tidak ditemukan.');
        
        if($request->target >= 100)
            return redirect()->back()->withInput()->with('warning', 'Target tidak boleh lebih dari 100%.');

        $unit = new UnitMonitoring();
        $unit->nama_unit = $request->nama_unit;
        $unit->orgeh = $request->orgeh;
        $unit->company_code = $request->company_code;
        $unit->sobid = @$orgeh->sobid;
        $unit->stxt2 = @$orgeh->stxt2;
        $unit->target_realisasi_coc = $request->target;
        $unit->save();
        
        return redirect('master-data/target-checkin-coc')->with('success', 'Target berhasil disimpan.');
    }

    public function editTargetCheckinCoc($id)
    {
        $unit = UnitMonitoring::find($id);
        
        return view('master.edit_target_checkin_coc', compact('unit'));
    }

    public function updateTargetCheckinCoc($id, Request $request)
    {
        // cek kode organisasi
        $orgeh = StrukturOrganisasi::where('objid', $request->orgeh)->first();

        if($orgeh==null)
            return redirect()->back()->withInput()->with('warning', 'Kode organisasi tidak ditemukan.');

        // cek company code
        $company_code = CompanyCode::where('company_code', $request->company_code)->first();
        if($company_code==null)
            return redirect()->back()->withInput()->with('warning', 'Company code tidak ditemukan.');
        
        if($request->target >= 100)
            return redirect()->back()->withInput()->with('warning', 'Target tidak boleh lebih dari 100%.');

        $unit = UnitMonitoring::find($id);
        $unit->nama_unit = $request->nama_unit;
        $unit->orgeh = $request->orgeh;
        $unit->company_code = $request->company_code;
        $unit->target_realisasi_coc = $request->target;
        $unit->save();
        
        return redirect('master-data/target-checkin-coc')->with('success', 'Target berhasil diubah.');
    }

    public function deleteTargetCheckinCoc($id)
    {
        $unit = UnitMonitoring::find($id);
        $unit->status = 'DEL';
        $unit->save();
        
        return redirect('master-data/target-checkin-coc')->with('success', 'Target berhasil dihapus.');
    }

    public function searchResult(Request $request)
    {
        if($request->tahun==0)
            return redirect('master-data/target-coc')->with('warning','Tahun belum dipilih');

        $tahun_selected = $request->tahun;
        $tahunList[0] = 'Select Tahun';
        for($x=date('Y')-2;$x<=date('Y')+5;$x++) {
            $tahunList[$x] = $x;
        }

        $jenjang_list = JenjangJabatan::orderBy('id', 'asc')->get();

        return view('master.target_coc', compact('tahunList', 'tahun_selected', 'jenjang_list'));
    }

    public function create($tahun, $jenjang_id)
    {
        $jenjang = JenjangJabatan::findOrFail($jenjang_id);
        
        return view('master.target_create', compact('tahun', 'jenjang'));
    }

    public function store(Request $request, $tahun, $jenjang_id)
    {
        $target = new TargetCoc();
        $target->tahun = $tahun;
        $target->jenjang_id = $jenjang_id;
        $target->t1_c1 = $request->t1_c1;
        $target->t1_c2 = $request->t1_c2;
        $target->t1_c3 = $request->t1_c3;

        $target->t2_c1 = $request->t2_c1;
        $target->t2_c2 = $request->t2_c2;
        $target->t2_c3 = $request->t2_c3;

        $target->t3_c1 = $request->t3_c1;
        $target->t3_c2 = $request->t3_c2;
        $target->t3_c3 = $request->t3_c3;

        $target->t4_c1 = $request->t4_c1;
        $target->t4_c2 = $request->t4_c2;
        $target->t4_c3 = $request->t4_c3;

        $target->save();

        return redirect('master-data/target-coc')->with('success', 'Target berhasil disimpan.');

    }

    public function edit($tahun, $jenjang_id)
    {
        $jenjang = JenjangJabatan::findOrFail($jenjang_id);
//        $target = $jenjang->targetCocTahun($tahun);
        $cluster1 = $jenjang->targetCoC()->where('tahun', $tahun)->where('cluster', 1)->first();
        $cluster2 = $jenjang->targetCoC()->where('tahun', $tahun)->where('cluster', 2)->first();
        $cluster3 = $jenjang->targetCoC()->where('tahun', $tahun)->where('cluster', 3)->first();

        return view('master.target_edit', compact('tahun', 'jenjang', 'cluster1', 'cluster2', 'cluster3'));
    }

    public function update(Request $request, $tahun, $jenjang_id)
    {
//        $jenjang = JenjangJabatan::findOrFail($jenjang_id);

        foreach(TargetCoc::where('tahun', $tahun)->where('jenjang_id', $jenjang_id)->get() as $target){
            $target->delete();
        }

//        dd('destroy');

        for($x=1; $x<=3;$x++) {
            $target = new TargetCoc();
            $target->tahun = $tahun;
            $target->jenjang_id = $jenjang_id;
            $target->cluster = $x;
            $target->tw1 = $request->get('t1_c'.$x);
            $target->tw2 = $request->get('t2_c'.$x);
            $target->tw3 = $request->get('t3_c'.$x);
            $target->tw4 = $request->get('t4_c'.$x);
            $target->save();
        }

//        $target = $jenjang->targetCocTahun($tahun);
//        $target->tahun = $tahun;
//        $target->jenjang_id = $jenjang_id;

//        $target->t1_c1 = $request->t1_c1;
//        $target->t1_c2 = $request->t1_c2;
//        $target->t1_c3 = $request->t1_c3;
//
//        $target->t2_c1 = $request->t2_c1;
//        $target->t2_c2 = $request->t2_c2;
//        $target->t2_c3 = $request->t2_c3;
//
//        $target->t3_c1 = $request->t3_c1;
//        $target->t3_c2 = $request->t3_c2;
//        $target->t3_c3 = $request->t3_c3;
//
//        $target->t4_c1 = $request->t4_c1;
//        $target->t4_c2 = $request->t4_c2;
//        $target->t4_c3 = $request->t4_c3;
//
//        $target->save();

        return redirect('master-data/target-coc')->with('success', 'Target berhasil diubah.');

    }
}
