<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Kompetensi;

use App\Http\Requests;
use App\StrukturJabatan;
use App\AssessmentPegawai;
use App\CompanyCode;
use App\Dirkom;
use App\PesertaAssessment;
use App\FeedbackAssessment;
use Illuminate\Http\Request;
use App\JabatanSelfAssessment;
use App\JadwalAssessment;
use App\KamusLevel;
use App\LevelKompetensiJabatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Date\Date;
use Maatwebsite\Excel\Facades\Excel;

class SelfAssessmentController extends Controller
{
    public function indexPegawai(Request $request)
    {
        Date::setLocale('id');
        // get last jadwal
        $jadwal = JadwalAssessment::where('status', 'ACTV')->orderBy('id', 'desc')->first();

        // search data peserta assessment
        $daftar_peserta = PesertaAssessment::with(['jadwal','jabatanPeserta','user','businessArea','companyCode','strukturPosisi'])->where('nip_pegawai',Auth::user()->nip)->where('status','ACTV')->orderBy('id','desc')->get();
        
        foreach (JabatanSelfAssessment::where('dirkom_id', $jadwal->dirkom_id)->orderBy('sebutan_jabatan','asc')->get() as $wa) {
            $jabatan_list[$wa->id] = $wa->sebutan_jabatan.', '.$wa->organisasi;
        }

        // // get jml level
        // $dirkom = $jadwal->dirkom;
        // $jml_level = $dirkom->jumlah_level;

        // // get hint jml level
        // $hint = "";
        // for($i=1;$i<=$jml_level;$i++){
        //     // first level
        //     if($i==1)
        //         $hint .= "'Level ".$i."'";
        //     else
        //         $hint .= ", 'Level ".$i."'";
        // }

        // get kamus level
        // $kamus_level = KamusLevel::where('dirkom_id', $jadwal->dirkom_id)->orderBy('level','asc')->get();

        return view('self_assessment.pegawai', compact('daftar_peserta', 'jabatan_list','kamus_level'));
    }

    public function indexVerifikator(Request $request)
    {
        // get year from url
        $tahun = request()->get('tahun');
        if($tahun == null)
            // get current year
            $tahun = Carbon::now()->year;

        // get bulan from url
        $bulan = request()->get('bulan');
        if($bulan == null){
            // get last month jadwal assessment
            $last_jadwal = JadwalAssessment::orderBy('id', 'desc')->first();
            $bulan = @$last_jadwal->bulan_periode;
        }

        // get array bulan
        $arr_bulan = array(
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
            '12' => 'Desember',
        );


        // get company_code
        $company_code = request()->get('company_code');
        if($company_code == null)
            $company_code = Auth::user()->company_code;
            
        // search data peserta assessment
        $daftar_peserta = PesertaAssessment::with(['jadwal','jabatanPeserta','user','businessArea','companyCode','strukturPosisi'])
                            ->where('nip_verifikator',Auth::user()->nip)
                            ->where('periode',$tahun)
                            ->where('bulan_periode',$bulan)
                            ->where('status','ACTV')
                            ->orderBy('nama_pegawai','asc')->get();
        
        foreach (JabatanSelfAssessment::orderBy('sebutan_jabatan','asc')->get() as $wa) {
            $jabatan_list[$wa->id] = $wa->sebutan_jabatan;
        }

        // get list company code
        $cc_selected = $company_code;
        $cc_list[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $cc_list[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

        return view('self_assessment.daftar_peserta', compact('daftar_peserta', 'jabatan_list', 'tahun', 'cc_list', 'cc_selected', 'arr_bulan', 'bulan'));
    }

    public function daftarPesertaVerifikator()
    {
        // dd('Daftar Peserta');

        $daftar_peserta = PesertaAssessment::with(['businessArea', 'jabatanPeserta'])->where('status','ACTV')->orderBy('nama_pegawai','asc')->get();

        foreach (JabatanSelfAssessment::orderBy('sebutan_jabatan','asc')->get() as $wa) {
            $jabatan_list[$wa->id] = $wa->sebutan_jabatan;
        }

        return view('self_assessment.daftar_peserta', compact('daftar_peserta', 'jabatan_list'));
    }

    public function getDetailKKJ($jabatan_id)
    {
        $jabatan = JabatanSelfAssessment::find($jabatan_id);

        // get jml level
        $dirkom = $jabatan->dirkom;
        $jml_level = $dirkom->jumlah_level;

        // get hint jml level
        $hint = "";
        for($i=1;$i<=$jml_level;$i++){
            // first level
            if($i==1)
                $hint .= "'Level ".$i."'";
            else
                $hint .= ", 'Level ".$i."'";
        }


        return view('self_assessment.detail_kkj',compact('jabatan','jml_level', 'hint'));
    }

    public static function getNamaBulan($index){
        $bulan = [
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
            '12' => 'Desember',
        ];

        return $bulan[$index];
    }

    public function getDetailKKJVerifikator($peserta_id)
    {
        $peserta = PesertaAssessment::find($peserta_id);

        // get string bulan periode from peserta
        $bulan_periode = $this->getNamaBulan($peserta->bulan_periode).' '.$peserta->periode;

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }

        // $jabatan = JabatanSelfAssessment::find($jabatan_id);
        $jabatan = $peserta->jabatanPeserta;
        if($jabatan!=null){
            // get list profesi
            $list_profesi = $jabatan->profesi();
            // get skor profesi
            $total_nilai_profesi = [];
            $total_nilai_kkj_profesi = [];
            $skor_profesi = [];
            $skor_gap_profesi = [];
            $rekomendasi_profesi = [];
            foreach ($list_profesi as $profesi) {
                $total_nilai_profesi[$profesi->profesi_id] = AssessmentPegawai::where('peserta_id', $peserta->id)->where('profesi_id', $profesi->profesi_id)->sum('level_final');
                $total_nilai_kkj_profesi[$profesi->profesi_id] = AssessmentPegawai::where('peserta_id', $peserta->id)->where('profesi_id', $profesi->profesi_id)->sum('level_kkj');

                if($total_nilai_kkj_profesi[$profesi->profesi_id]!=0){
                    $skor_profesi[$profesi->profesi_id] = $total_nilai_profesi[$profesi->profesi_id]/$total_nilai_kkj_profesi[$profesi->profesi_id];
                    $skor_gap_profesi[$profesi->profesi_id] = ($total_nilai_kkj_profesi[$profesi->profesi_id]-$total_nilai_profesi[$profesi->profesi_id])/$total_nilai_kkj_profesi[$profesi->profesi_id];
                    $rekomendasi_profesi[$profesi->profesi_id] = $this->calculateRekomendasi($skor_profesi[$profesi->profesi_id]);
                }
            }

            // get list dahan profesi
            $list_dahan = $jabatan->dahanProfesi();
            // get skor dahan profesi
            $total_nilai_dahan = [];
            $total_nilai_kkj_dahan = [];
            $skor_dahan = [];
            $skor_gap_dahan = [];
            $rekomendasi_dahan = [];
            foreach ($list_dahan as $dahan) {
                $total_nilai_dahan[$dahan->dahan_profesi_id] = AssessmentPegawai::where('peserta_id', $peserta->id)->where('dahan_profesi_id', $dahan->dahan_profesi_id)->sum('level_final');
                $total_nilai_kkj_dahan[$dahan->dahan_profesi_id] = AssessmentPegawai::where('peserta_id', $peserta->id)->where('dahan_profesi_id', $dahan->dahan_profesi_id)->sum('level_kkj');

                if($total_nilai_kkj_dahan[$dahan->dahan_profesi_id]!=0){
                    $skor_dahan[$dahan->dahan_profesi_id] = $total_nilai_dahan[$dahan->dahan_profesi_id]/$total_nilai_kkj_dahan[$dahan->dahan_profesi_id];
                    $skor_gap_dahan[$dahan->dahan_profesi_id] = ($total_nilai_kkj_dahan[$dahan->dahan_profesi_id]-$total_nilai_dahan[$dahan->dahan_profesi_id])/$total_nilai_kkj_dahan[$dahan->dahan_profesi_id];
                    $rekomendasi_dahan[$dahan->dahan_profesi_id] = $this->calculateRekomendasi($skor_dahan[$dahan->dahan_profesi_id]);
                }
            }

            return view('self_assessment.detail_kkj_verifikator',compact('peserta','jabatan', 'list_profesi', 'skor_profesi', 'skor_gap_profesi', 'rekomendasi_profesi', 'list_dahan', 'skor_dahan', 'skor_gap_dahan', 'rekomendasi_dahan', 'total_nilai_profesi', 'total_nilai_kkj_profesi', 'total_nilai_dahan', 'total_nilai_kkj_dahan', 'bulan_periode'));
        }
        return '<div class="alert alert-danger alert-dismissible fade in m-t-20" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Perhatian!</strong> Jabatan Assessment tidak ditemukan. Silakan update Jabatan Asssessment terlebih dahulu.
                </div>';
    }

    public function calculateRekomendasi($skor)
    {
        if($skor>=env('SKOR_MEET_ATAS',0.8))
            return 'Exceed';
        else if($skor>env('SKOR_MEET_BAWAH',0.6) && $skor<env('SKOR_MEET_ATAS',0.8))
            return 'Meet';
        else if($skor<=env('SKOR_MEET_BAWAH',0.6))
            return 'Below';
        else
            return 'N/A';
    }

    public function prioritasPengembangan($peserta_id)
    {
        $peserta = PesertaAssessment::find($peserta_id);

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }

        // $jabatan = JabatanSelfAssessment::find($jabatan_id);
        $jabatan = $peserta->jabatanPeserta;
        if($jabatan!=null)
            return view('self_assessment.prioritas_pengembangan',compact('peserta','jabatan'));

        return '<div class="alert alert-danger alert-dismissible fade in m-t-20" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Perhatian!</strong> Jabatan Assessment tidak ditemukan. Silakan update Jabatan Asssessment terlebih dahulu.
                </div>';
    }

    public function setPrioritasPengembangan($assessment_id)
    {
        // dd($assessment_id);
        $assessment_pegawai = AssessmentPegawai::find($assessment_id);

        if($assessment_pegawai==null){
            return redirect()->back()->with('error','Assessment pegawai tidak ditemukan');
        }

        // reset prioritas
        $reset_assessment = AssessmentPegawai::where('prioritas','1')->where('peserta_id',$assessment_pegawai->peserta_id)->get();
        foreach($reset_assessment as $assessment){
            $assessment->prioritas = '';
            $assessment->save();
        }

        $assessment_pegawai->prioritas = 1;
        $assessment_pegawai->save();

        if($assessment_pegawai->peserta==null){
            return redirect()->back()->with('error','Peserta self assessment pegawai tidak ditemukan');
        }

        $peserta = $assessment_pegawai->peserta;
        $peserta->prioritas_assessmen_id = $assessment_pegawai->id;
        $peserta->save();

        return redirect()->back()->with('success','Prioritas pengembangan berhasil ditetapkan. Klik tombol Approve Assessment untuk menyetujui hasil penilaian.');

    }

    public function getDetailKompetensi($kode_kompetensi)
    {
        $kompetensi = Kompetensi::where('kode',$kode_kompetensi)->first();
        return view('self_assessment.detail_kompetensi',compact('kompetensi'));
    }

    public function editKompetensi(Request $request)
    {
        $peserta_id = $request->get('peserta_id');
        $kode_kompetensi = $request->get('kode_kompetensi');
        $score = $request->get('score');

        $peserta = PesertaAssessment::find($peserta_id);

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }

        // get jml level
        $jadwal = $peserta->jadwal;
        $dirkom = $jadwal->dirkom;
        $jml_level = $dirkom->jumlah_level;

        // get hint jml level
        $hint = "";
        for($i=1;$i<=$jml_level;$i++){
            // first level
            if($i==1)
                $hint .= "'Level ".$i."'";
            else
                $hint .= ", 'Level ".$i."'";
        }

        $kompetensi = Kompetensi::where('kode',$kode_kompetensi)->where('dirkom_id', $dirkom->id)->first();
        $assessment = $peserta->assessmentPegawai()->where('kompetensi_id', $kompetensi->id)->first();

        return view('self_assessment.edit_kompetensi',compact('peserta','kompetensi','score','assessment', 'jml_level', 'hint'));
    }

    public function editKompetensiVerifikator(Request $request)
    {
        $peserta_id = $request->get('peserta_id');
        $kode_kompetensi = $request->get('kode_kompetensi');
        $score = $request->get('score');

        $peserta = PesertaAssessment::find($peserta_id);

        // get jml level
        $jadwal = $peserta->jadwal;
        $dirkom = $jadwal->dirkom;
        $jml_level = $dirkom->jumlah_level;

        // get hint jml level
        $hint = "";
        for($i=1;$i<=$jml_level;$i++){
            // first level
            if($i==1)
                $hint .= "'Level ".$i."'";
            else
                $hint .= ", 'Level ".$i."'";
        }

        $kompetensi = Kompetensi::where('kode',$kode_kompetensi)->where('dirkom_id', $dirkom->id)->first();
        $assessment = $peserta->assessmentPegawai()->where('kode_kompetensi', $kode_kompetensi)->first();

        return view('self_assessment.edit_kompetensi_verifikator',compact('peserta','kompetensi','score','assessment', 'jml_level', 'hint'));
    }

    public function updateKompetensi(Request $request)
    {
        // dd($request);

        $peserta_id = $request->get('peserta_id');
        $assessment_id = $request->get('assessment_id');
        $kode_kompetensi = $request->get('kode_kompetensi');
        $level = $request->get('level');
        $usulan_pengembangan = $request->get('usulan_pengembangan');
        $keterangan = $request->get('keterangan');
        $evidence = $request->file('evidence');

        // peserta
        $peserta = PesertaAssessment::find($peserta_id);

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }
        
        // kompetensi jabatan peserta
        $level_kom_jab = LevelKompetensiJabatan::where('kode_kompetensi', $kode_kompetensi)->where('jabatan_id', $peserta->jabatan_id)->first();

        $kompetensi = $level_kom_jab->kompetensi;

        // dd($kompetensi);

        // jika file > 2 MB
        if($evidence!=null){
            $size = $evidence->getSize();
            if ($size > 2000000) {
                return redirect()->back()->with('warning', 'Ukuran file yang diupload melebihi 2 MB');
            }
        }

        // jika tidak ada data assessment, create new data
        if($assessment_id==''){

            // cek apakah sudah ada penilaian atau belum
            $penilaian_pegawai = AssessmentPegawai::where('nip',$peserta->nip_pegawai)->where('kode_kompetensi',$kode_kompetensi)->where('peserta_id',$peserta_id)->first();

            if($penilaian_pegawai!=null){
                return redirect()->back()->with('error','Penilaian untuk kode '.$kode_kompetensi.' sudah ada.');
            }
            
            $assessment = new AssessmentPegawai();
            
        }
         // jika ada data assessment, update data
        else{
            $assessment = AssessmentPegawai::find($assessment_id);
        }

        $assessment->peserta_id = $peserta_id;
        $assessment->nip = $peserta->nip_pegawai;
        
        $assessment->jabatan_id = $peserta->jabatan_id;
        $assessment->kode_kompetensi = $kode_kompetensi;
        $assessment->kompetensi_id = $level_kom_jab->kompetensi_id;

        $assessment->level_kkj = $level_kom_jab->level;
        $assessment->level_pegawai = $level;
        $assessment->level_final = $level;
        $assessment->gap_level = $assessment->level_final - $assessment->level_kkj;

        // jika ada file, simpan
        if($evidence!=null){
            // $extension = strtolower($evidence->getClientOriginalExtension());
            $filename = date('YmdHis').'_'.$evidence->getClientOriginalName();
            Storage::put('self-assessment/' . $filename, File::get($evidence)); 
            $assessment->evidence = $filename;
        }
        
        $assessment->usulan_pengembangan = $usulan_pengembangan;
        $assessment->keterangan = $keterangan;
        $assessment->tanggal_input = Carbon::now();

        // simpan dahan dan profesi
        $assessment->dahan_profesi_id = $kompetensi->dahan_profesi_id;
        $assessment->kode_dahan_profesi = $kompetensi->kode_dahan;
        $assessment->profesi_id = $kompetensi->profesi_id;
        $assessment->kode_profesi = $kompetensi->kode_profesi;

        $assessment->save();

        // cek apakah sudah menilai semua kompetensi

        // get jabatan peserta
        $jabatan = $peserta->jabatanPeserta;

        // get jumlah kompetensi jabatan
        $jml_kompetensi_jabatan = $jabatan->kompetensi->count();

        // get jumlah assessment
        $jml_asesmen_pegawai = $peserta->assessmentPegawai->count();

        // dd($peserta->assessmentPegawai);
        // dd($jml_kompetensi_jabatan.' - '.$jml_asesmen_pegawai);

        // update status
        if($jml_kompetensi_jabatan==$jml_asesmen_pegawai){
            $peserta->status_assessment = 'Penilaian lengkap';
            $peserta->save();
            $string_msg = 'Penilaian sudah lengkap. Silakan klik Send for Approval untuk dikirimkan ke verifikator.';
        }
        else{
            $peserta->status_assessment = 'Penilaian belum lengkap';
            $peserta->save();
            $string_msg = 'Kompetensi '.@$assessment->kompetensi->judul_kompetensi.' berhasil di-update';
        }
        
        return redirect()->back()->with('success', $string_msg);
    }

    public function updateKompetensiVerifikator(Request $request)
    {
        // dd($request);

        $peserta_id = $request->get('peserta_id');
        $assessment_id = $request->get('assessment_id');
        $kode_kompetensi = $request->get('kode_kompetensi');
        $level = $request->get('level');
        $usulan_pengembangan = $request->get('usulan_pengembangan');
        $keterangan = $request->get('keterangan');
        $evidence = $request->file('evidence');

        // peserta
        $peserta = PesertaAssessment::find($peserta_id);

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }
        
        // kompetensi jabatan peserta
        $level_kom_jab = LevelKompetensiJabatan::where('kode_kompetensi', $kode_kompetensi)->where('jabatan_id', $peserta->jabatan_id)->first();

        // jika file > 2 MB
        if($evidence!=null){
            $size = $evidence->getSize();
            if ($size > 2000000) {
                return redirect()->back()->with('warning', 'Ukuran file yang diupload melebihi 2 MB');
            }
        }

        // jika tidak ada data assessment, create new data
        if($assessment_id==''){
            // $assessment = new AssessmentPegawai();
            return redirect()->back()->with('error','Peserta belum melakukan penilaian.');
        }
         // jika ada data assessment, update data
        else{
            $assessment = AssessmentPegawai::find($assessment_id);
        }

        $assessment->peserta_id = $peserta_id;
        $assessment->nip = $peserta->nip_pegawai;

        $assessment->jabatan_id = $peserta->jabatan_id;
        $assessment->kode_kompetensi = $kode_kompetensi;
        $assessment->kompetensi_id = $level_kom_jab->kompetensi_id;

        $assessment->level_penyelarasan = $level;
        $assessment->level_final = $level;
        $assessment->level_kkj = $level_kom_jab->level;
        $assessment->gap_level = $assessment->level_final - $assessment->level_kkj;

        // dd($assessment->level_final.' - '.$assessment->level_kkj);

        // jika ada file, simpan
        if($evidence!=null){
            // $extension = strtolower($evidence->getClientOriginalExtension());
            $filename = date('YmdHis').'_'.$evidence->getClientOriginalName();
            Storage::put('self-assessment/' . $filename, File::get($evidence)); 
            $assessment->evidence = $filename;
        }
        
        $assessment->usulan_pengembangan = $usulan_pengembangan;
        $assessment->keterangan = $keterangan;
        $assessment->tanggal_verifikasi = Carbon::now();

        $assessment->save();

        // // cek apakah sudah menilai semua kompetensi

        // // get jabatan peserta
        // $jabatan = $peserta->jabatanPeserta;

        // // get jumlah kompetensi jabatan
        // $jml_kompetensi_jabatan = $jabatan->kompetensi->count();

        // // get jumlah assessment
        // $jml_asesmen_pegawai = $peserta->assessmentPegawai->count();

        // // update status
        // if($jml_kompetensi_jabatan==$jml_asesmen_pegawai){
        //     $peserta->status_assessment = 'Sudah dilakukan penilaian';
        //     $peserta->save();
        // }
        
        return redirect()->back()->with('success', 'Kompetensi '.@$assessment->kompetensi->judul_kompetensi.' berhasil di-update');      
    }

    public function updateJabatan(Request $request)
    {
        $peserta_id = $request->get('peserta_id');
        $jabatan_id = $request->get('jabatan_id');

        $peserta = PesertaAssessment::find($peserta_id);

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }

        $peserta->jabatan_id = $jabatan_id;
        $peserta->save();

        return redirect()->back()->with('success','Jabatan berhasil di-update.');
    }

    public function updateVerifikator(Request $request)
    {
        $peserta_id = $request->get('peserta_id_verifikator');
        $nip_verifikator = $request->get('nip_verifikator');
        $verifikator = StrukturJabatan::where('nip', $nip_verifikator)->first();
        $posisi_verifikator = @$verifikator->strukturPosisi;

        $peserta = PesertaAssessment::find($peserta_id);

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }

        $peserta->nip_verifikator = $nip_verifikator;
        $peserta->nama_verifikator = $verifikator->cname;
        $peserta->jabatan_verifikator = @$posisi_verifikator->stext;
        $peserta->kode_posisi_verifikator = $verifikator->plans;
        $peserta->save();

        return redirect()->back()->with('success','Verifikator berhasil di-update.');
    }


    public function updateHardskill(Request $request)
    {
        $peserta_id = $request->get('peserta_id');
        $hardskill = $request->get('hardskill');

        $peserta = PesertaAssessment::find($peserta_id);

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }

        $peserta->hardskill = $hardskill;
        $peserta->save();

        return redirect()->back()->with('success','Hardskill berhasil di-update.');
    }

    public function approvePengukuran($peserta_id, Request $request)
    {
        $peserta = PesertaAssessment::find($peserta_id);

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }

        // jika prioritas belum ditentukan
        if($peserta->prioritas_assessmen_id==''){
            return redirect()->back()->with('warning','Prioritas pengembangan belum ditentukan. Silakah pilih prioritas pengembangan pegawai dengan klik tombol Prioritas Pengembangan.');
        }

        // update status peserta
        $peserta->status_assessment = 'Disetujui';
        $peserta->save();

        // update status assessment
        foreach($peserta->assessmentPegawai as $assessment){
            $assessment->tanggal_approve = Carbon::now();
            $assessment->save();
        }      

        // cek apakah sudah input feedback
        $feedback = FeedbackAssessment::where('nip', Auth::user()->nip)->first();
        
        if($feedback!=null){
            return redirect()->back()->with('success','Self Assessment Pegawai Disetujui.');
        }
        else{
            return redirect()->back()->with('feedback','Feedback');
        }
    }

    public function unapprovePengukuran($peserta_id)
    {
        $peserta = PesertaAssessment::find($peserta_id);

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }

        // update status peserta
        $peserta->status_assessment = 'Persetujuan dibatalkan';
        $peserta->save();

        // update status assessment
        foreach($peserta->assessmentPegawai as $assessment){
            $assessment->tanggal_approve = null;
            $assessment->save();
        }      

        return redirect()->back()->with('success','Persetujuan Self Assessment Pegawai berhasil dibatalkan.');
    }

    public function sendPengukuran($peserta_id)
    {
        $peserta = PesertaAssessment::find($peserta_id);

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }

        // update status peserta
        $peserta->status_assessment = 'Siap diverifikasi';
        $peserta->save();    

        // cek apakah sudah input feedback
        $feedback = FeedbackAssessment::where('nip', Auth::user()->nip)->first();
        
        if($feedback!=null){
            return redirect()->back()->with('success','Self Assessment Pegawai Terkirim.');
        }
        else{
            return redirect()->back()->with('feedback','Feedback');
        }
    }

    public function daftarPeserta()
    {
        // get year from url
        $tahun = request()->get('tahun');
        if($tahun == null)
            // get current year
            $tahun = Carbon::now()->year;

        // get bulan from url
        $bulan = request()->get('bulan');
        if($bulan == null){
            // get last month jadwal assessment
            $last_jadwal = JadwalAssessment::orderBy('id', 'desc')->first();
            $bulan = @$last_jadwal->bulan_periode;
        }

        // get array bulan
        $arr_bulan = array(
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
            '12' => 'Desember',
        );

        // get company_code
        $company_code = request()->get('company_code');
        if($company_code == null)
            $company_code = Auth::user()->company_code;

        // dd('Daftar Peserta');

        $daftar_peserta = PesertaAssessment::with(['businessArea', 'jabatanPeserta'])
                            ->where('periode', $tahun)
                            ->where('bulan_periode', $bulan)
                            ->where('company_code', $company_code)
                            ->where('status','ACTV')
                            ->orderBy('nama_pegawai','asc')
                            // ->take(5)
                            ->get();

        $jadwal = JadwalAssessment::where('periode', $tahun)->where('bulan_periode', $bulan)->orderBy('id', 'desc')->first();

        if($jadwal == null)
            return redirect('/')->with('error','Jadwal assessment tidak ditemukan pada periode tersebut.');
        
        foreach (JabatanSelfAssessment::where('dirkom_id', $jadwal->dirkom_id)->orderBy('sebutan_jabatan','asc')->get() as $wa) {
            $jabatan_list[$wa->id] = $wa->sebutan_jabatan.', '.$wa->organisasi;
        }

        // get list company code
        $cc_selected = $company_code;
        $cc_list[0] = 'Select Company Code';
        foreach (CompanyCode::all()->sortBy('id') as $wa) {
            $cc_list[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
        }

        return view('self_assessment.daftar_peserta', compact('daftar_peserta', 'jabatan_list', 'tahun', 'cc_list', 'cc_selected', 'arr_bulan', 'bulan'));
    }

    public function exportRekap()
    {
        // dd('Daftar Peserta');
        Date::setLocale('id');

        // get company_code
        $company_code = request()->get('company_code');
        if($company_code == null)
            $company_code = Auth::user()->company_code;

        // get year from url
        $tahun = request()->get('tahun');
        if($tahun == null)
            // get current year
            $tahun = Carbon::now()->year;
        
        // get bulan from url
        $bulan = request()->get('bulan');
        if($bulan == null){
            // get last month jadwal assessment
            $last_jadwal = JadwalAssessment::orderBy('id', 'desc')->first();
            $bulan = @$last_jadwal->bulan_periode;
        }

        $daftar_peserta = PesertaAssessment::with(['businessArea', 'jabatanPeserta'])
                            ->where('company_code', $company_code)
                            ->where('bulan_periode', $bulan)
                            ->where('periode', $tahun)
                            ->where('status','ACTV')->orderBy('nama_pegawai','asc')->get();
        
        Excel::create(date('YmdHis').'_rekap_pengukuran',
            function ($excel) use ($daftar_peserta) {

                $excel->sheet('Rekap Penilaian', function ($sheet) use ($daftar_peserta) {
                    $sheet->loadView('report/template_rekap_assessment', [
                        'daftar_peserta'=>$daftar_peserta
                    ]);
                });

            })->download('xlsx');

        // return view('self_assessment.daftar_peserta', compact('daftar_peserta'));
    }

    public function detailPeserta($peserta_id)
    {
        // dd($peserta_id);

        // search data peserta assessment
        $peserta = PesertaAssessment::with(['jadwal','jabatanPeserta','user','businessArea','companyCode','strukturPosisi'])->where('id',$peserta_id)->first();
        $periode_bulan = $this->getNamaBulan($peserta->bulan_periode).' '.$peserta->periode;
        $dirkom = @$peserta->jadwal->dirkom;

        if($dirkom!=null){
            $jml_level = $dirkom->jumlah_level;
        }
        else{
            $jml_level = 0;
        }

        // get hint jml level
        $hint = "";
        for($i=1;$i<=$jml_level;$i++){
            // first level
            if($i==1)
                $hint .= "'Level ".$i."'";
            else
                $hint .= ", 'Level ".$i."'";
        }

        // get kamus level
        $kamus_level = KamusLevel::where('dirkom_id', $dirkom->id)->orderBy('level','asc')->get();
        
        foreach (JabatanSelfAssessment::orderBy('sebutan_jabatan','asc')->get() as $wa) {
            $jabatan_list[$wa->id] = $wa->sebutan_jabatan;
        }

        return view('self_assessment.detail_assessment', compact('peserta', 'jabatan_list', 'hint', 'kamus_level', 'jml_level', 'periode_bulan'));
    }

    public function submitPeserta(Request $request)
    {
        $nip_pegawai = $request->get('nip_peserta');
        $jabatan_id = $request->get('jabatan_peserta_id');
        $nip_verifikator = $request->get('nip_verifikator_peserta');
        
        // get last jadwal di tahun aktif
        $jadwal = JadwalAssessment::where('periode',date('Y'))->orderBy('id', 'desc')->first();

        // cek pegawai apakah sudah menjadi peserta assessment
        $cek_peserta = PesertaAssessment::where('jadwal_id',$jadwal->id)->where('nip_pegawai', $nip_pegawai)->where('status','ACTV')->first();
        if($cek_peserta!=null)
            return redirect()->back()->with('warning','Pegawai '.$cek_peserta->nama_pegawai.' sudah terdaftar sebagai Peserta Self Assessment');

        // save peserta
        $pegawai = StrukturJabatan::where('nip', $nip_pegawai)->first();
        $jabatan_pegawai = $pegawai->strukturPosisi;
        $user_pegawai = User::where('nip', $nip_pegawai)->first();

        $verifikator= StrukturJabatan::where('nip', $nip_verifikator)->first();
        $jabatan_verifikator= $verifikator->strukturPosisi;

        $peserta = new PesertaAssessment();
        $peserta->jadwal_id = $jadwal->id;
        $peserta->jabatan_id = $jabatan_id;

        $peserta->nip_pegawai = $nip_pegawai;
        $peserta->nama_pegawai = $pegawai->cname;
        $peserta->jabatan_pegawai = $jabatan_pegawai->stext;
        $peserta->company_code = $user_pegawai->company_code;
        $peserta->business_area = $user_pegawai->business_area;
        $peserta->kode_posisi = $jabatan_pegawai->objid;
        $peserta->posisi = $jabatan_pegawai->stext;

        $peserta->nip_verifikator = $nip_verifikator;
        $peserta->nama_verifikator = $verifikator->cname;
        $peserta->jabatan_verifikator = $jabatan_verifikator->stext;
        $peserta->kode_posisi_verifikator = $jabatan_verifikator->objid;
        $peserta->periode = $jadwal->periode;
        $peserta->bulan_periode = $jadwal->bulan_periode;

        $peserta->save();

        return redirect()->back()->with('success','Peserta Self Assessment berhasil disimpan.');
    }

    public function removePeserta($peserta_id)
    {
        $peserta = PesertaAssessment::find($peserta_id);

        if($peserta==null){
            return redirect()->back()->with('error','Data peserta tidak ditemukan');
        }

        // update status peserta
        $peserta->status = 'DEL';
        $peserta->save();

        // update status assessment
        foreach($peserta->assessmentPegawai as $assessment){
            $assessment->status = 'DEL';
            $assessment->save();
        }      

        return redirect()->back()->with('success','Peserta Assessment berhasil dihapus.');
    }

    public function submitFeedback(Request $request)
    {
        $feedback = new FeedbackAssessment;
        $feedback->user_id = Auth::user()->id;
        $feedback->nip = Auth::user()->nip;
        $feedback->nama = Auth::user()->name;
        $feedback->feedback = $request->get('feedback');
        $feedback->save();

        return redirect()->back()->with('success','Terimakasih telah memberikan feedback.');
    }

    public function dataAjaxKamusLevel(Request $request)
    {
        if ($request->has('dirkom_id')) {
            $dirkom_id = $request->dirkom_id;
            $dirkom = Dirkom::find($dirkom_id);
             // get kamus level
            $kamus_level = KamusLevel::where('dirkom_id', $dirkom_id)->orderBy('level','asc')->get();
            $jml_level = $dirkom->jumlah_level;

            // get hint jml level
            $hint = "";
            for($i=1;$i<=$jml_level;$i++){
                // first level
                if($i==1)
                    $hint .= "'Level ".$i."'";
                else
                    $hint .= ", 'Level ".$i."'";
            }
        }

        return view('self_assessment.ajax_kamus_level', compact('kamus_level', 'jml_level', 'hint'));
    }
}
