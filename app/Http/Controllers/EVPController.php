<?php

namespace App\Http\Controllers;

use App\Activity;
use App\BusinessArea;
use App\CompanyCode;
use App\EVP;
use App\Http\Requests\ProgramRequest;
use App\JenisEVP;
use App\JenisWaktuEVP;
use App\RundownEVP;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class EVPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lokasi_selected = $request->lokasi;
        $nama_kegiatan = $request->nama_kegiatan;

        // role admin pusat / root
        if(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat')){
            $lokasi_list = EVP::where('status', 'ACTV')->get()->unique('tempat')->pluck('tempat');
            if ($lokasi_selected != '' || $nama_kegiatan != '') {
                if ($lokasi_selected == 'all') {
                    $evp_list = EVP::where('status', 'ACTV')
                        ->whereRaw(DB::raw("LOWER(nama_kegiatan) like '%" . strtolower($nama_kegiatan) . "%'"))
                        ->orderBy('id', 'desc')->get();
                } else {
                    $evp_list = EVP::where('status', 'ACTV')
                        ->where('tempat', $lokasi_selected)
                        ->whereRaw(DB::raw("LOWER(nama_kegiatan) like '%" . strtolower($nama_kegiatan) . "%'"))
                        ->orderBy('id', 'desc')->get();
                }
            } else
                $evp_list = EVP::where('status', 'ACTV')->orderBy('id', 'desc')->get();
        }

        // role admin evp
        elseif(Auth::user()->hasRole('admin_evp')) {
            $lokasi_list = EVP::where('status', 'ACTV')
                ->whereHas('businessArea', function($q){
                    $q->whereIn('id',Auth::user()->companyCode->businessArea->pluck('id')->toArray());
                })->get()->unique('tempat')->pluck('tempat');

            if ($lokasi_selected != '' || $nama_kegiatan != '') {
                if ($lokasi_selected == 'all') {
                    $evp_list = EVP::where('status', 'ACTV')
                        ->whereRaw(DB::raw("LOWER(nama_kegiatan) like '%" . strtolower($nama_kegiatan) . "%'"))
                        ->whereHas('businessArea', function($q)
                        {
                            $q->whereIn('id',Auth::user()->companyCode->businessArea->pluck('id')->toArray());
                        })
                        ->orderBy('id', 'desc')->get();
                } else {
                    $evp_list = EVP::where('status', 'ACTV')
                        ->where('tempat', $lokasi_selected)
                        ->whereRaw(DB::raw("LOWER(nama_kegiatan) like '%" . strtolower($nama_kegiatan) . "%'"))
                        ->whereHas('businessArea', function($q)
                        {
                            $q->whereIn('id',Auth::user()->companyCode->businessArea->pluck('id')->toArray());
                        })
                        ->orderBy('id', 'desc')->get();
                }
            } else
                $evp_list = EVP::where('status', 'ACTV')
                    ->whereHas('businessArea', function($q)
                    {
                        $q->whereIn('id',Auth::user()->companyCode->businessArea->pluck('id')->toArray());
                    })
                    ->orderBy('id', 'desc')->get();
        }

        // role pegawai
        else {
            $lokasi_list = EVP::where('status', 'ACTV')
                                ->whereHas('businessArea', function($q){
                                    $q->whereId(Auth::user()->businessArea->id);
                                })->get()->unique('tempat')->pluck('tempat');
            if ($lokasi_selected != '' || $nama_kegiatan != '') {
                if ($lokasi_selected == 'all') {
                    $evp_list = EVP::where('status', 'ACTV')
                        ->whereRaw(DB::raw("LOWER(nama_kegiatan) like '%" . strtolower($nama_kegiatan) . "%'"))
                        ->whereHas('businessArea', function($q)
                        {
                            $q->whereId(Auth::user()->businessArea->id);
                        })
                        ->orderBy('id', 'desc')->get();
                } else {
                    $evp_list = EVP::where('status', 'ACTV')
                        ->where('tempat', $lokasi_selected)
                        ->whereRaw(DB::raw("LOWER(nama_kegiatan) like '%" . strtolower($nama_kegiatan) . "%'"))
                        ->whereHas('businessArea', function($q)
                        {
                            $q->whereId(Auth::user()->businessArea->id);
                        })
                        ->orderBy('id', 'desc')->get();
                }
            } else
                $evp_list = EVP::where('status', 'ACTV')
                            ->whereHas('businessArea', function($q)
                            {
                                $q->whereId(Auth::user()->businessArea->id);
                            })
                            ->orderBy('id', 'desc')->get();
        }
//        dd($evp_list);

        return view('evp.program_list', compact('evp_list', 'lokasi_list', 'lokasi_selected', 'nama_kegiatan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
//        dd($id);
        $jenis_evp = JenisEVP::find($id);
//        dd($jenis_evp);
        $jenis_waktu_list = JenisWaktuEVP::lists('description', 'id');
//        dd($jenis_waktu_list);

        $cc_list = CompanyCode::where('status','ACTV')->orderBy('company_code')->get();
        $ba_list = BusinessArea::where('status','ACTV')->orderBy('business_area')->get();
//        foreach(BusinessArea::orderBy('business_area')->get() as $ba){
//            $ba_list[$ba->id] = $ba->business_area.' - '.$ba->description;
//        }

        return view('evp.program_create', compact('jenis_evp', 'jenis_waktu_list', 'cc_list', 'ba_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProgramRequest $request)
    {
//        dd($request);

        $evp = new EVP();

        $evp->jenis_evp_id = $request->jenis_evp_id;

        $evp->nama_kegiatan = $request->nama_kegiatan;
        $evp->deskripsi = $request->deskripsi;
        $evp->kriteria_peserta = $request->kriteria_peserta;
        $evp->jenis_waktu_id = $request->jenis_waktu_evp_id;
        $evp->kuota = $request->kuota;

        $evp->tempat = $request->tempat;
        $evp->waktu_awal = Carbon::parse($request->waktu_awal);
        $evp->waktu_akhir = Carbon::parse($request->waktu_akhir);
        $evp->tgl_awal_registrasi = Carbon::parse($request->tgl_registrasi_awal);
        $evp->tgl_akhir_registrasi = Carbon::parse($request->tgl_registrasi_akhir);
        $evp->tgl_pengumuman = Carbon::parse($request->tgl_pengumuman);
        $evp->briefing = $request->briefing;
        $evp->tempat_briefing = $request->tempat_briefing;
        $evp->tgl_jam_briefing = Carbon::parse($request->tgl_briefing . ' ' . $request->jam_briefing);

        $evp->nama_vendor = $request->nama_vendor;
        $evp->email_vendor = $request->email_vendor;

        $evp->reg_atasan = $request->reg_atasan;
        $evp->reg_gm = $request->reg_gm;
        $evp->reg_admin_lv1 = $request->reg_admin_lv1;
        $evp->reg_admin_pusat = $request->reg_admin_pusat;

        $evp->keg_atasan = $request->keg_atasan;
        $evp->keg_vendor = $request->keg_vendor;

        $foto = $request->file('foto');
        $dokumen = $request->file('dokumen');
        $data_diri = $request->file('data_diri');
        $surat_pernyataan = $request->file('surat_pernyataan');

        if ($foto != null) {
            $evp->foto = $foto->getClientOriginalName();

            Storage::put('evp/foto/' . $foto->getClientOriginalName(), File::get($foto));
        }

        if ($dokumen != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $evp->dokumen = $dokumen->getClientOriginalName();

            Storage::put('evp/attachment/' . $dokumen->getClientOriginalName(), File::get($dokumen));
        }
        if ($data_diri != null) {
//            $extension = strtolower($data_diri->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $evp->data_diri = $data_diri->getClientOriginalName();

            Storage::put('evp/attachment/' . $data_diri->getClientOriginalName(), File::get($data_diri));
        }
        if ($surat_pernyataan != null) {
//            $extension = strtolower($surat_pernyataan->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $evp->surat_pernyataan = $surat_pernyataan->getClientOriginalName();

            Storage::put('evp/attachment/' . $surat_pernyataan->getClientOriginalName(), File::get($surat_pernyataan));
        }

        $evp->save();

        // save unit
        $arr_company_code = $request->company_code;
        foreach ($arr_company_code as $cc) {
            $evp->companyCode()->attach($cc);
        }

        $arr_business_area = $request->business_area;
        foreach ($arr_business_area as $ba) {
            $evp->businessArea()->attach($ba);
        }

        // save workplan
        $arr_tgl_awal_wp = array_filter(explode(',', $request->arr_tgl_awal_wp));
        $arr_tgl_akhir_wp = array_filter(explode(',', $request->arr_tgl_akhir_wp));
        $arr_jam_awal_wp = array_filter(explode(',', $request->arr_jam_awal_wp));
        $arr_jam_akhir_wp = array_filter(explode(',', $request->arr_jam_akhir_wp));
        $arr_lokasi_wp = array_filter(explode(',', $request->arr_lokasi_wp));
        $arr_kegiatan_wp = array_filter(explode(',', $request->arr_kegiatan_wp));
        $arr_penanggungjawab_wp = array_filter(explode(',', $request->arr_penanggungjawab_wp));


        for ($x = 1; $x <= count($arr_tgl_awal_wp); $x++) {
            $rundown = new RundownEVP();

            $rundown->evp_id = $evp->id;
            $rundown->kegiatan = $arr_kegiatan_wp[$x];
            $rundown->lokasi = $arr_lokasi_wp[$x];
            $rundown->tgl_jam_awal = Carbon::parse($arr_tgl_awal_wp[$x] . ' ' . $arr_jam_awal_wp[$x]);
            $rundown->tgl_jam_akhir = Carbon::parse($arr_tgl_akhir_wp[$x] . ' ' . $arr_jam_akhir_wp[$x]);
            $rundown->penanggungjawab = $arr_penanggungjawab_wp[$x];
            $rundown->save();

        }


        // activity log
        Activity::log('Create EVP Program: '.$evp->nama_kegiatan.'; ID: '.$evp->id.'.', 'success');

        return redirect('evp/program')->with('success', 'Program berhasil disimpan.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evp = EVP::find($id);
//        $jenis_waktu_list = JenisWaktuEVP::lists('description', 'id');
        if($evp->jenis_evp_id == 1) {
            $volunteer_list = $evp->volunteers()->whereIn('status', ['APRV-PST','BRFG', 'ACTV', 'COMP'])->get();
        }
        else{
            $volunteer_list = $evp->volunteers()->whereIn('status', ['APRV-ADM','BRFG', 'ACTV', 'COMP'])->get();
        }
        return view('evp.program_detail', compact('evp', 'volunteer_list'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $evp = EVP::find($id);

        if($evp->jenis_evp_id=='1' && !(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat'))){
            return redirect('/evp/program')->with('warning', 'You are not authorized.');
        }

//        $jenis_evp = JenisEVP::find($evp->jenis_evp_id);
        $jenis_waktu_list = JenisWaktuEVP::lists('description', 'id');

        $cc_list = CompanyCode::where('status','ACTV')->orderBy('company_code')->get();
        $ba_list = BusinessArea::where('status','ACTV')->orderBy('business_area')->get();

        return view('evp.program_edit', compact('evp', 'jenis_waktu_list', 'cc_list', 'ba_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, ProgramRequest $request)
    {
//        dd($request);

        $evp = EVP::find($id);

        $evp->jenis_evp_id = $request->jenis_evp_id;

        $evp->nama_kegiatan = $request->nama_kegiatan;
        $evp->deskripsi = $request->deskripsi;
        $evp->kriteria_peserta = $request->kriteria_peserta;
        $evp->jenis_waktu_id = $request->jenis_waktu_evp_id;
        $evp->kuota = $request->kuota;

        $evp->tempat = $request->tempat;
        $evp->waktu_awal = Carbon::parse($request->waktu_awal);
        $evp->waktu_akhir = Carbon::parse($request->waktu_akhir);
        $evp->tgl_awal_registrasi = Carbon::parse($request->tgl_registrasi_awal);
        $evp->tgl_akhir_registrasi = Carbon::parse($request->tgl_registrasi_akhir);
        $evp->tgl_pengumuman = Carbon::parse($request->tgl_pengumuman);
        $evp->briefing = $request->briefing;
        $evp->tempat_briefing = $request->tempat_briefing;
        $evp->tgl_jam_briefing = Carbon::parse($request->tgl_briefing . ' ' . $request->jam_briefing);

        $evp->nama_vendor = $request->nama_vendor;
        $evp->email_vendor = $request->email_vendor;

        $evp->reg_atasan = $request->reg_atasan;
        $evp->reg_gm = $request->reg_gm;
        $evp->reg_admin_lv1 = $request->reg_admin_lv1;
        $evp->reg_admin_pusat = $request->reg_admin_pusat;

        $evp->keg_atasan = $request->keg_atasan;
        $evp->keg_vendor = $request->keg_vendor;

        $foto = $request->file('foto');
        $dokumen = $request->file('dokumen');
        $data_diri = $request->file('data_diri');
        $surat_pernyataan = $request->file('surat_pernyataan');

        if ($foto != null) {
            $evp->foto = $foto->getClientOriginalName();

            Storage::put('evp/foto/' . $foto->getClientOriginalName(), File::get($foto));
        }

        if ($dokumen != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $evp->dokumen = $dokumen->getClientOriginalName();

            Storage::put('evp/attachment/' . $dokumen->getClientOriginalName(), File::get($dokumen));
        }
        if ($data_diri != null) {
//            $extension = strtolower($data_diri->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $evp->data_diri = $data_diri->getClientOriginalName();

            Storage::put('evp/attachment/' . $data_diri->getClientOriginalName(), File::get($data_diri));
        }
        if ($surat_pernyataan != null) {
//            $extension = strtolower($surat_pernyataan->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $evp->surat_pernyataan = $surat_pernyataan->getClientOriginalName();

            Storage::put('evp/attachment/' . $surat_pernyataan->getClientOriginalName(), File::get($surat_pernyataan));
        }

        $evp->save();

        // clear unit
        $evp->companyCode()->sync([]);
        $evp->businessArea()->sync([]);

        // save unit
        $arr_company_code = $request->company_code;
        foreach ($arr_company_code as $cc) {
            $evp->companyCode()->attach($cc);
        }

        $arr_business_area = $request->business_area;
        foreach ($arr_business_area as $ba) {
            $evp->businessArea()->attach($ba);
        }

        //clear workplan
        $evp->rundownEVP()->delete();

        // save workplan
        $arr_tgl_awal_wp = array_filter(explode(',', $request->arr_tgl_awal_wp));
        $arr_tgl_akhir_wp = array_filter(explode(',', $request->arr_tgl_akhir_wp));
        $arr_jam_awal_wp = array_filter(explode(',', $request->arr_jam_awal_wp));
        $arr_jam_akhir_wp = array_filter(explode(',', $request->arr_jam_akhir_wp));
        $arr_lokasi_wp = array_filter(explode(',', $request->arr_lokasi_wp));
        $arr_kegiatan_wp = array_filter(explode(',', $request->arr_kegiatan_wp));
        $arr_penanggungjawab_wp = array_filter(explode(',', $request->arr_penanggungjawab_wp));


        for ($x = 1; $x <= count($arr_tgl_awal_wp); $x++) {
            $rundown = new RundownEVP();

            $rundown->evp_id = $evp->id;
            $rundown->kegiatan = $arr_kegiatan_wp[$x];
            $rundown->lokasi = $arr_lokasi_wp[$x];
            $rundown->tgl_jam_awal = Carbon::parse($arr_tgl_awal_wp[$x] . ' ' . $arr_jam_awal_wp[$x]);
            $rundown->tgl_jam_akhir = Carbon::parse($arr_tgl_akhir_wp[$x] . ' ' . $arr_jam_akhir_wp[$x]);
            $rundown->penanggungjawab = $arr_penanggungjawab_wp[$x];
            $rundown->save();

        }

        // activity log
        Activity::log('Update EVP Program: '.$evp->nama_kegiatan.'; ID: '.$evp->id.'.', 'success');

        return redirect('evp/program')->with('success', 'Program berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ajaxEVPList($lokasi)
    {
        if ($lokasi == 'all')
            $evp_list = EVP::where('status', 'ACTV')->orderBy('id', 'desc')->get();
        else
            $evp_list = EVP::where('status', 'ACTV')->where('tempat', $lokasi)->orderBy('id', 'desc')->get();

        return view('evp.ajax_program_list', compact('evp_list'));
    }

    public function indexApproval(Request $request)
    {

        // role admin pusat / root
        if(Auth::user()->hasRole('root') ){
            $evp_list = EVP::where('status', 'ACTV')->orderBy('id', 'desc')->get();
        }

        elseif(Auth::user()->hasRole('admin_pusat')){
            $evp_list = EVP::where('status', 'ACTV')->where('jenis_evp_id', '1')->orderBy('id', 'desc')->get();
        }

        // role admin evp
        elseif(Auth::user()->hasRole('admin_evp')) {
            $evp_list = EVP::where('status', 'ACTV')
                ->whereHas('businessArea', function($q)
                {
                    $q->whereIn('id',Auth::user()->companyCode->businessArea->pluck('id')->toArray());
                })
                ->orderBy('id', 'desc')->get();
        }

        // role GM
        elseif(Auth::user()->isGM()) {
            $evp_list = EVP::where('status', 'ACTV')
                ->where('jenis_evp_id','1')
                ->whereHas('businessArea', function($q)
                {
                    $q->whereIn('id',Auth::user()->companyCode->businessArea->pluck('id')->toArray());
                })
                ->orderBy('id', 'desc')->get();
        }

        // role Atasan
        else {
                $evp_list = EVP::where('status', 'ACTV')
                    ->whereHas('businessArea', function($q)
                    {
                        $q->whereId(Auth::user()->businessArea->id);
                    })
                    ->orderBy('id', 'desc')->get();
        }
//        dd($evp_list);

        return view('evp.approval_list', compact('evp_list'));
    }

    public function help(){
        $filename = 'app/user_manual_evp.pdf';
        $path = storage_path($filename);

        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);
    }
}
