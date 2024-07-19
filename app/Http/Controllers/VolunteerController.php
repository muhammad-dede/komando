<?php

namespace App\Http\Controllers;

use App\Activity;
use App\EVP;
use App\Http\Requests\VolunteerRequest;
use App\JenjangJabatan;
use App\MailLog;
use App\Notification;
use App\Role;
use App\StatusVolunteer;
use App\StrukturJabatan;
use App\Volunteer;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class VolunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $pernr_atasan = Auth::user()->strukturJabatan->pernr_at;
//        $atasan = StrukturJabatan::where('pernr', $pernr_atasan)->first();

//        $gm = JenjangJabatan::getListPejabat(Auth::user()->company_code, 1)->first()->jabatan;

//        dd(Auth::user()->volunteer);
        if (Auth::user()->isGM()) {
            $atasan = null;
            $pernr_atasan = null;
            $gm = null;
        } else {
            $atasan = Auth::user()->getStrukjabAtasan();
            if($atasan==null)
                return redirect('/evp/program')->with('error', 'Gagal mengambil data atasan langsung. Silakan hubungi Administrator.');
            $pernr_atasan = $atasan->pernr;
            $gm = Auth::user()->getStrukjabGM();
        }
//        $gm = JenjangJabatan::getListPejabat(Auth::user()->company_code, 1)->first()->jabatan;
//        dd(Auth::user()->can('evp_log'));

        return view('evp.dashboard_evp', compact('evp', 'pernr_atasan', 'atasan', 'gm'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        // cek sudah daftar
        $volunteer = Volunteer::where('user_id', Auth::user()->id)->where('evp_id', $id)->first();
        if ($volunteer != null) {
            return redirect('evp/program')->with('warning', 'Anda sudah mendaftar pada kegiatan ini. Silakan cek status registrasi Anda pada menu Employee Volunteer Program / Dashboard');
        }

        // cek tanggal pendaftaran
        $evp = EVP::find($id);
        $now = Carbon::now();
        if (!($evp->tgl_awal_registrasi->format('Ymd') <= $now->format('Ymd') && $evp->tgl_akhir_registrasi->format('Ymd') >= $now->format('Ymd'))) {
            return redirect('evp/program')->with('warning', 'Anda tidak dapat mendaftar pada kegiatan ini dikarenakan pendaftaran telah berakhir.');
        }

        // cek unit
        $cc_user = Auth::user()->companyCode;
        $ba_user = Auth::user()->businessArea;
        if (!($evp->companyCode()->where('id', $cc_user->id)->first() != null && $evp->businessArea()->where('id', $ba_user->id)->first() != null)) {
            return redirect('evp/program')->with('warning', 'Anda tidak dapat mendaftar pada kegiatan ini dikarenakan Unit Anda tidak terdaftar pada program. Silakan cek kembali detil program pada bagian Unit.');
        }

        $evp = EVP::find($id);

//        $pernr_atasan = Auth::user()->strukturJabatan->pernr_at;
//        $atasan = StrukturJabatan::where('pernr', $pernr_atasan)->first();

        $atasan = Auth::user()->getStrukjabAtasan();
        if($atasan==null)
            return redirect('/evp/program')->with('error', 'Gagal mengambil data atasan langsung. Silakan hubungi Administrator.');
        $pernr_atasan = $atasan->pernr;

//        $gm = JenjangJabatan::getListPejabat(Auth::user()->company_code, 1)->first()->jabatan;
        $gm = Auth::user()->getStrukjabGM();
//        dd($gm->jabatan);

        return view('evp.register', compact('evp', 'pernr_atasan', 'atasan', 'gm'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(VolunteerRequest $request, $id)
    {
//        dd($request);
//        $id = $request->evp_id;

//        if (Auth::user()->checkVolunteer($request->evp_id) == true) {
//            return redirect('evp/program')->with('warning', 'Anda sudah mendaftar, silakan cek status registrasi Anda pada menu Employee Volunteer Program / Dashboard');
//        }

        // cek sudah daftar
        $volunteer = Volunteer::where('user_id', Auth::user()->id)->where('evp_id', $id)->first();
        if ($volunteer != null) {
            return redirect('evp/program')->with('warning', 'Anda sudah mendaftar pada kegiatan ini. Silakan cek status registrasi Anda pada menu Employee Volunteer Program / Dashboard');
        }

        // cek tanggal pendaftaran
        $evp = EVP::find($id);
        $now = Carbon::now();
        if (!($evp->tgl_awal_registrasi->format('Ymd') <= $now->format('Ymd') && $evp->tgl_akhir_registrasi->format('Ymd') >= $now->format('Ymd'))) {
            return redirect('evp/program')->with('warning', 'Anda tidak dapat mendaftar pada kegiatan ini dikarenakan pendaftaran telah berakhir.');
        }

        // cek unit
        $cc_user = Auth::user()->companyCode;
        $ba_user = Auth::user()->businessArea;
        if (!($evp->companyCode()->where('id', $cc_user->id)->first() != null && $evp->businessArea()->where('id', $ba_user->id)->first() != null)) {
            return redirect('evp/program')->with('warning', 'Anda tidak dapat mendaftar pada kegiatan ini dikarenakan Unit Anda tidak terdaftar pada program. Silakan cek kembali detil program pada bagian Unit.');
        }

        $volunteer = new Volunteer();

        $volunteer->evp_id = $request->evp_id;
        $volunteer->user_id = Auth::user()->id;
        $volunteer->nama = Auth::user()->name;
        $volunteer->nip = Auth::user()->nip;
        $volunteer->company_code = Auth::user()->company_code;
        $volunteer->business_area = Auth::user()->business_area;
        $volunteer->jabatan = $request->jabatan;
        $volunteer->bidang = $request->bidang;
        $volunteer->pernr_atasan = $request->pernr_atasan;

        $volunteer->acc_atasan = $evp->reg_atasan;

        if ($evp->reg_gm == '1') {
            $volunteer->acc_gm = $evp->reg_gm;
            $volunteer->pernr_gm = $request->pernr_gm;
        }

        if ($evp->jenis_evp_id == '1')
            $volunteer->acc_pusat = '1';

        $volunteer->answer_tertarik = $request->answer_tertarik;
        $volunteer->answer_tepat = $request->answer_tepat;

        $file_cv = $request->file('file_cv');
        $file_surat_pernyataan = $request->file('file_surat_pernyataan');
        $file_surat_ijin_gm = $request->file('file_surat_ijin_gm');
        $file_surat_ijin_keluarga = $request->file('file_surat_ijin_keluarga');
        $file_surat_sehat = $request->file('file_surat_sehat');

        if ($file_cv != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $filename = date('YmdHis_') . $file_cv->getClientOriginalName();
            $volunteer->file_cv = $filename;

            Storage::put('evp/volunteer/' . $filename, File::get($file_cv));
        }

        if ($file_surat_pernyataan != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $filename = date('YmdHis_') . $file_surat_pernyataan->getClientOriginalName();
            $volunteer->file_surat_pernyataan = $filename;

            Storage::put('evp/volunteer/' . $filename, File::get($file_surat_pernyataan));
        }

        if ($file_surat_ijin_gm != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $filename = date('YmdHis_') . $file_surat_ijin_gm->getClientOriginalName();
            $volunteer->file_surat_ijin_gm = $filename;

            Storage::put('evp/volunteer/' . $filename, File::get($file_surat_ijin_gm));
        }

        if ($file_surat_ijin_keluarga != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $filename = date('YmdHis_') . $file_surat_ijin_keluarga->getClientOriginalName();
            $volunteer->file_surat_ijin_keluarga = $filename;

            Storage::put('evp/volunteer/' . $filename, File::get($file_surat_ijin_keluarga));
        }

        if ($file_surat_sehat != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $filename = date('YmdHis_') . $file_surat_sehat->getClientOriginalName();
            $volunteer->file_surat_sehat = $filename;

            Storage::put('evp/volunteer/' . $filename, File::get($file_surat_sehat));
        }

//        $volunteer->registrasi = Carbon::now();
        $volunteer->status = 'REG';
        $volunteer->save();

        // save log
        $status = new StatusVolunteer();
        $status->volunteer_id = $volunteer->id;
        $status->message = $volunteer->nama . ' telah melakukan registrasi untuk kegiatan ' . $volunteer->evp->nama_kegiatan;
        $status->status = 'REG';
        $status->save();

        $volunteer->registrasi = $status->created_at;
        $volunteer->save();

        // send email to atasan
        $atasan = StrukturJabatan::where('pernr', $request->pernr_atasan)->first();

        $user = $atasan->user;
        if ($user != null) {
//        $user = User::find();
            $notif = new Notification();
            $notif->from = Auth::user()->username2;
            $notif->to = $user->username2;
            $notif->user_id_from = Auth::user()->id;
            $notif->user_id_to = $user->id;
            $notif->subject = 'Persetujuan EVP ' . $volunteer->nama;
//            $notif->color = 'pink';
//            $notif->icon = 'fa fa-heart-o';

            $notif->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
            $notif->url = 'evp/volunteer/' . $volunteer->id;

            $notif->save();


            $mail = new MailLog();
            $mail->to = $user->email;
            $mail->to_name = $user->name;
            $mail->subject = '[KOMANDO] Permohonan Persetujuan ' . $volunteer->nama . ' untuk Menjadi Relawan Kegiatan "' . $evp->nama_kegiatan . '"';
            $mail->file_view = 'emails.evp_approval';
            $mail->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
            $mail->status = 'CRTD';
            $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","nama":"' . $volunteer->nama . '","nip":"' . $volunteer->nip . '","posisi":"' . $volunteer->jabatan . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
            $mail->notification_id = $notif->id;
            $mail->jenis = '4';

            $mail->save();
        }

        // activity log
        Activity::log('Register EVP: ' . $evp->nama_kegiatan . '; ID: ' . $evp->id . '.', 'success');

        return redirect('evp/dashboard')->with('success', 'Terimakasih atas partisipasi Anda. Status registasi dapat dilihat pada menu Employee Volunteer Program / Dashboard.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $volunteer = Volunteer::find($id);
        $evp = $volunteer->evp;
//        $pernr_atasan = Auth::user()->strukturJabatan->pernr_at;
        $atasan = StrukturJabatan::where('pernr', $volunteer->pernr_atasan)->first();
        $pernr_atasan = $atasan->pernr;
//        $gm = JenjangJabatan::getListPejabat(Auth::user()->company_code, 1)->first()->jabatan;
        $gm = StrukturJabatan::where('pernr', $volunteer->pernr_gm)->first();
//        dd($gm->jabatan);

        return view('evp.register_detail', compact('evp', 'pernr_atasan', 'atasan', 'gm', 'volunteer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $volunteer = Volunteer::find($id);
        if ($volunteer->status != 'REG') {
            return redirect('evp/dashboard')->with('warning', 'Anda sudah tidak diperbolehkan mengubah data.');
        }

        $evp = $volunteer->evp;
//        $atasan = Auth::user()->getStrukjabAtasan();
//        if($atasan==null)
//            return redirect('/evp/program')->with('error', 'Gagal mengambil data atasan langsung. Silakan hubungi Administrator.');
//        $pernr_atasan = Auth::user()->strukturJabatan->pernr_at;
        $atasan = StrukturJabatan::where('pernr', $volunteer->pernr_atasan)->first();
        $pernr_atasan = $atasan->pernr;

        $gm = StrukturJabatan::where('pernr', $volunteer->pernr_gm)->first();
//        $gm = Auth::user()->getStrukjabGM();
//        dd($pernr_atasan);

        return view('evp.register_edit', compact('evp', 'pernr_atasan', 'atasan', 'gm', 'volunteer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        // cek sudah daftar
//        $volunteer = Volunteer::where('user_id', Auth::user()->id)->where('evp_id', $id)->first();
//        if ($volunteer != null) {
//            return redirect('evp/program')->with('warning', 'Anda sudah mendaftar pada kegiatan ini. Silakan cek status registrasi Anda pada menu Employee Volunteer Program / Dashboard');
//        }
//
//        // cek tanggal pendaftaran
//        $evp = EVP::find($id);
//        $now = Carbon::now();
//        if (!($evp->tgl_awal_registrasi <= $now && $evp->tgl_akhir_registrasi >= $now)) {
//            return redirect('evp/program')->with('warning', 'Anda tidak dapat mendaftar pada kegiatan ini dikarenakan pendaftaran telah berakhir.');
//        }
//
//        // cek unit
//        $cc_user = Auth::user()->companyCode;
//        $ba_user = Auth::user()->businessArea;
//        if (!($evp->companyCode()->where('id', $cc_user->id)->first() != null && $evp->businessArea()->where('id', $ba_user->id)->first() != null)) {
//            return redirect('evp/program')->with('warning', 'Anda tidak dapat mendaftar pada kegiatan ini dikarenakan Unit Anda tidak terdaftar pada program. Silakan cek kembali detil program pada bagian Unit.');
//        }

//        dd($request);

        $volunteer = Volunteer::find($id);

        if ($volunteer->status != 'REG') {
            return redirect('evp/dashboard')->with('warning', 'Anda sudah tidak diperbolehkan mengubah data.');
        }

        $evp = $volunteer->evp;

//        $volunteer->evp_id = $request->evp_id;
//        $volunteer->user_id = Auth::user()->id;
//        $volunteer->nama = Auth::user()->name;
//        $volunteer->nip = Auth::user()->nip;
        $volunteer->company_code = Auth::user()->company_code;
        $volunteer->business_area = Auth::user()->business_area;
        $volunteer->jabatan = $request->jabatan;
        $volunteer->bidang = $request->bidang;
        $volunteer->pernr_atasan = $request->pernr_atasan;
        $volunteer->pernr_gm = $request->pernr_gm;

        $volunteer->answer_tertarik = $request->answer_tertarik;
        $volunteer->answer_tepat = $request->answer_tepat;

        $file_cv = $request->file('file_cv');
        $file_surat_pernyataan = $request->file('file_surat_pernyataan');
        $file_surat_ijin_gm = $request->file('file_surat_ijin_gm');
        $file_surat_ijin_keluarga = $request->file('file_surat_ijin_keluarga');
        $file_surat_sehat = $request->file('file_surat_sehat');

        if ($file_cv != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $filename = date('YmdHis_') . $file_cv->getClientOriginalName();
            $volunteer->file_cv = $filename;

            Storage::put('evp/volunteer/' . $filename, File::get($file_cv));
        }

        if ($file_surat_pernyataan != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $filename = date('YmdHis_') . $file_surat_pernyataan->getClientOriginalName();
            $volunteer->file_surat_pernyataan = $filename;

            Storage::put('evp/volunteer/' . $filename, File::get($file_surat_pernyataan));
        }

        if ($file_surat_ijin_gm != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $filename = date('YmdHis_') . $file_surat_ijin_gm->getClientOriginalName();
            $volunteer->file_surat_ijin_gm = $filename;

            Storage::put('evp/volunteer/' . $filename, File::get($file_surat_ijin_gm));
        }

        if ($file_surat_ijin_keluarga != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $filename = date('YmdHis_') . $file_surat_ijin_keluarga->getClientOriginalName();
            $volunteer->file_surat_ijin_keluarga = $filename;

            Storage::put('evp/volunteer/' . $filename, File::get($file_surat_ijin_keluarga));
        }

        if ($file_surat_sehat != null) {
//            $extension = strtolower($dokumen->getClientOriginalExtension());
//            if ($extension != 'pdf') {
//                return redirect('evp/create')->with('warning', 'File yang diupload bukan berekstensi PDF.');
//            }

            $filename = date('YmdHis_') . $file_surat_sehat->getClientOriginalName();
            $volunteer->file_surat_sehat = $filename;

            Storage::put('evp/volunteer/' . $filename, File::get($file_surat_sehat));
        }

//        $volunteer->registrasi = Carbon::now();
//        $volunteer->status = 'REG';
        $volunteer->save();

        // save log
        $status = new StatusVolunteer();
        $status->volunteer_id = $volunteer->id;
        $status->message = $volunteer->nama . ' telah melakukan edit data untuk kegiatan ' . $volunteer->evp->nama_kegiatan;
        $status->status = 'REG';
        $status->save();

        $volunteer->registrasi = $status->created_at;
        $volunteer->save();

        // activity log
        Activity::log('Edit EVP: ' . $evp->nama_kegiatan . '; ID: ' . $evp->id . '.', 'success');

        return redirect('evp/dashboard')->with('success', 'Data berhasil diubah.');
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

    public function approvalIndex()
    {

        if (Auth::user()->hasRole('root')) {
            $volunteer_list = Volunteer::orderBy('id', 'desc')->get();
        } elseif (Auth::user()->can('evp_approve')) {
            $volunteer_list = Volunteer::where('acc_pusat', '1')->orderBy('id', 'desc')->get();
        } elseif (Auth::user()->isGM()) {
            $volunteer_list = Volunteer::with(['evp'])
                ->where('pernr_gm', Auth::user()->pa0032->pernr)
                ->where('acc_gm', '1')
                ->whereNotNull('approval_atasan')
                ->orderBy('id', 'desc')
                ->get();
        } elseif (Auth::user()->isStruktural()) {
            $volunteer_list = Volunteer::where('pernr_atasan', Auth::user()->pa0032->pernr)->orderBy('id', 'desc')->get();
        }

//        dd($volunteer_list);
        return view('evp.approval', compact('volunteer_list'));
    }

    public function _approveEVP($id)
    {
        if (!(Auth::user()->can('evp_approve') || Auth::user()->isStruktural() || Auth::user()->isGM()))
            return redirect('evp/program')->with('warning', 'You have no authorization!');

        $volunteer = Volunteer::find($id);

        // approval oleh atasan
        if (Auth::user()->isStruktural() && $volunteer->pernr_atasan == Auth::user()->strukturJabatan->pernr && $volunteer->approval_atasan == null) {
//            $volunteer->approval_atasan = Carbon::now();
            $volunteer->status = 'APRV-AT';
            $volunteer->save();

            $log = StatusVolunteer::log($volunteer->id, 'Permohonan telah disetujui oleh atasan langsung.', 'APRV-AT', Auth::user()->id);

            $volunteer->approval_atasan = $log->created_at;
            $volunteer->save();

            return redirect('evp/approval')->with('success', 'Terimakasih atas persetujuan yang telah Anda berikan.');
        } else {
            if ($volunteer->pernr_atasan != Auth::user()->strukturJabatan->pernr)
                return redirect('evp/approval')->with('warning', 'Approval gagal! Anda bukan Atasan pegawai yang bersangkutan.');
            else
                return redirect('evp/approval')->with('warning', 'Approval gagal! Permohonan sudah disetujui.');
        }

        // approval oleh GM
        if (Auth::user()->isGM() &&
            $volunteer->pernr_gm == Auth::user()->strukturJabatan->pernr &&
            $volunteer->approval_atasan != null &&
            $volunteer->approval_gm == null
        ) {

            $volunteer->approval_gm = Carbon::now();
            $volunteer->save();
        }
        // approval oleh pusat


    }

    public function approveEVP($id, $approver)
    {

        if (!(Auth::user()->can('evp_approve') || Auth::user()->isStruktural() || Auth::user()->isGM()))
            return redirect('evp/approval')->with('warning', 'You have no authorization!');

        $volunteer = Volunteer::find($id);
        $evp = $volunteer->evp;

//        dd($volunteer);

        // ID APPROVE :
        // 1 : Atasan
        // 2 : Admin EVP
        // 3 : GM
        // 4 : Pusat

        // approve oleh atasan
        if ($approver == '1') {
            if (Auth::user()->isStruktural() &&
                $volunteer->pernr_atasan == Auth::user()->strukturJabatan->pernr &&
                $volunteer->approval_atasan == null
            ) {
//            $volunteer->approval_atasan = Carbon::now();
                $volunteer->status = 'APRV-AT';
                $volunteer->save();

                $log = StatusVolunteer::log($volunteer->id, 'Permohonan telah disetujui oleh atasan langsung.', 'APRV-AT', Auth::user()->id);

                $volunteer->approval_atasan = $log->created_at;
                $volunteer->save();

                // activity log
                Activity::log('Approve EVP: ' . $evp->nama_kegiatan . '; ID Volunteer: ' . $volunteer->id . '.', 'success');

                if ($evp->reg_admin_lv1 == '1') {
                    // send email to Admin EVP
                    $role_admin = Role::find(7);

                    foreach ($role_admin->users()->where('company_code', $volunteer->company_code)->get() as $user) {
//                    $user = $atasan->user;
                        if ($user != null) {
                            $notif = new Notification();
                            $notif->from = Auth::user()->username2;
                            $notif->to = $user->username2;
                            $notif->user_id_from = Auth::user()->id;
                            $notif->user_id_to = $user->id;
                            $notif->subject = 'Pengajuan EVP ' . $volunteer->nama;

                            $notif->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                            $notif->url = 'evp/volunteer/' . $volunteer->id;

                            $notif->save();

                            $mail = new MailLog();
                            $mail->to = $user->email;
                            $mail->to_name = $user->name;
                            $mail->subject = '[KOMANDO] Permohonan Review ' . $volunteer->nama . ' Untuk Menjadi Relawan Kegiatan "' . $evp->nama_kegiatan . '"';
                            $mail->file_view = 'emails.evp_approval_lv1';
                            $mail->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                            $mail->status = 'CRTD';
                            $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","nama":"' . $volunteer->nama . '","nip":"' . $volunteer->nip . '","posisi":"' . $volunteer->jabatan . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
                            $mail->notification_id = $notif->id;
                            $mail->jenis = '4';

                            $mail->save();
                        }
                    }
                }
                /*
                // if REG_GM==1 -> send email to GM
                elseif ($evp->reg_gm == '1') {

                    $atasan = StrukturJabatan::where('pernr', $volunteer->pernr_gm)->first();

                    $user = $atasan->user;
                    if ($user != null) {
                        $notif = new Notification();
                        $notif->from = Auth::user()->username2;
                        $notif->to = $user->username2;
                        $notif->user_id_from = Auth::user()->id;
                        $notif->user_id_to = $user->id;
                        $notif->subject = 'Persetujuan EVP ' . $volunteer->nama;

                        $notif->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                        $notif->url = 'evp/volunteer/' . $volunteer->id;

                        $notif->save();

                        $mail = new MailLog();
                        $mail->to = $user->email;
                        $mail->to_name = $user->name;
                        $mail->subject = '[KOMANDO] Permohonan Persetujuan ' . $volunteer->nama . ' untuk Menjadi Relawan Kegiatan "' . $evp->nama_kegiatan . '"';
                        $mail->file_view = 'emails.evp_approval_gm';
                        $mail->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                        $mail->status = 'CRTD';
                        $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","nama":"' . $volunteer->nama . '","nip":"' . $volunteer->nip . '","posisi":"' . $volunteer->jabatan . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
                        $mail->notification_id = $notif->id;
                        $mail->jenis = '4';

                        $mail->save();
                    }
                }
                */
                /*
                // jika reg_gm!='1' -> send notification to Admin Pusat
                else{
                    // send email to Admin Pusat
//                $atasan = StrukturJabatan::where('pernr', $volunteer->pernr_gm)->first();
                    $role_pusat = Role::find(3);

                    foreach ($role_pusat->users as $user) {
//                    $user = $atasan->user;
                        if ($user != null) {
                            $notif = new Notification();
                            $notif->from = Auth::user()->username2;
                            $notif->to = $user->username2;
                            $notif->user_id_from = Auth::user()->id;
                            $notif->user_id_to = $user->id;
                            $notif->subject = 'Pengajuan EVP ' . $volunteer->nama;

                            $notif->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                            $notif->url = 'evp/volunteer/' . $volunteer->id;

                            $notif->save();

                            $mail = new MailLog();
                            $mail->to = $user->email;
                            $mail->to_name = $user->name;
                            $mail->subject = '[KOMANDO] Permohonan Review ' . $volunteer->nama . ' Untuk Menjadi Relawan Kegiatan "' . $evp->nama_kegiatan . '"';
                            $mail->file_view = 'emails.evp_approval_pusat';
                            $mail->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                            $mail->status = 'CRTD';
                            $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","nama":"' . $volunteer->nama . '","nip":"' . $volunteer->nip . '","posisi":"' . $volunteer->jabatan . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
                            $mail->notification_id = $notif->id;
                            $mail->jenis = '4';

                            $mail->save();
                        }
                    }
                }
                */

                // send email to Pegawai
                $user = $volunteer->user;
                if ($user != null) {
                    $notif = new Notification();
                    $notif->from = Auth::user()->username2;
                    $notif->to = $user->username2;
                    $notif->user_id_from = Auth::user()->id;
                    $notif->user_id_to = $user->id;
                    $notif->subject = 'Persetujuan EVP ' . $evp->nama_kegiatan;

                    $notif->message = 'Permohonan telah disetujui oleh atasan langsung untuk program "' . $evp->nama_kegiatan . '"';
                    $notif->url = 'evp/volunteer/' . $volunteer->id;

                    $notif->save();

                    $mail = new MailLog();
                    $mail->to = $user->email;
                    $mail->to_name = $user->name;
                    $mail->subject = '[KOMANDO] Permohonan Relawan Telah Disetujui oleh ' . Auth::user()->name;
                    $mail->file_view = 'emails.evp_confirm_approve_at';
                    $mail->message = $volunteer->nama . ' disetujui oleh atasan langsung sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                    $mail->status = 'CRTD';
                    $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","atasan":"' . Auth::user()->name . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
                    $mail->notification_id = $notif->id;
                    $mail->jenis = '4';

                    $mail->save();
                }

                return redirect('evp/approval-atasan')->with('success', 'Terimakasih atas persetujuan yang telah Anda berikan.');
            } else {
                if ($volunteer->pernr_atasan != Auth::user()->strukturJabatan->pernr)
                    return redirect('evp/approval-atasan')->with('warning', 'Approval atasan gagal! Anda bukan Atasan pegawai yang bersangkutan.');
                elseif ($volunteer->approval_atasan != null)
                    return redirect('evp/approval-atasan')->with('warning', 'Approval atasan gagal! Permohonan sudah disetujui.');
                else
                    return redirect('evp/approval-atasan')->with('error', 'Error Approval Atasan! Hubungi Administrator.');
            }
        } // approve oleh Admin EVP
        elseif ($approver == '2') {
            if (Auth::user()->hasRole('admin_evp') &&
//                $volunteer->pernr_gm == Auth::user()->strukturJabatan->pernr &&
                $volunteer->approval_atasan != null &&
                $volunteer->approval_admin_lv1 == null
            ) {
//            $volunteer->approval_atasan = Carbon::now();
                $volunteer->status = 'APRV-ADM';
                $volunteer->save();

                $log = StatusVolunteer::log($volunteer->id, 'Permohonan Telah Disetujui oleh Admin EVP Unit Induk.', 'APRV-ADM', Auth::user()->id);

                $volunteer->approval_admin = $log->created_at;
                $volunteer->save();

                // activity log
                Activity::log('Approve EVP: ' . $evp->nama_kegiatan . '; ID Volunteer: ' . $volunteer->id . '.', 'success');

                // send email to Admin Pusat
//                $atasan = StrukturJabatan::where('pernr', $volunteer->pernr_gm)->first();
//                $role_pusat = Role::find(3);
//
//                foreach ($role_pusat->users as $user) {
////                    $user = $atasan->user;
//                    if ($user != null) {
//                        $notif = new Notification();
//                        $notif->from = Auth::user()->username2;
//                        $notif->to = $user->username2;
//                        $notif->user_id_from = Auth::user()->id;
//                        $notif->user_id_to = $user->id;
//                        $notif->subject = 'Pengajuan EVP ' . $volunteer->nama;
//
//                        $notif->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
//                        $notif->url = 'evp/volunteer/' . $volunteer->id;
//
//                        $notif->save();
//
//                        $mail = new MailLog();
//                        $mail->to = $user->email;
//                        $mail->to_name = $user->name;
//                        $mail->subject = '[KOMANDO] Permohonan Review ' . $volunteer->nama . ' Untuk Menjadi Relawan Kegiatan "' . $evp->nama_kegiatan . '"';
//                        $mail->file_view = 'emails.evp_approval_pusat';
//                        $mail->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
//                        $mail->status = 'CRTD';
//                        $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","nama":"' . $volunteer->nama . '","nip":"' . $volunteer->nip . '","posisi":"' . $volunteer->jabatan . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
//                        $mail->notification_id = $notif->id;
//                        $mail->jenis = '4';
//
//                        $mail->save();
//                    }
//                }

                // send email to Pegawai
                $user = $volunteer->user;
                if ($user != null) {
                    $notif = new Notification();
                    $notif->from = Auth::user()->username2;
                    $notif->to = $user->username2;
                    $notif->user_id_from = Auth::user()->id;
                    $notif->user_id_to = $user->id;
                    $notif->subject = 'Persetujuan EVP ' . $evp->nama_kegiatan;

                    $notif->message = 'Permohonan telah disetujui oleh Admin EVP untuk program "' . $evp->nama_kegiatan . '"';
                    $notif->url = 'evp/volunteer/' . $volunteer->id;

                    $notif->save();

                    $mail = new MailLog();
                    $mail->to = $user->email;
                    $mail->to_name = $user->name;
                    $mail->subject = '[KOMANDO] Permohonan Relawan Telah Disetujui oleh Admin EVP';
                    $mail->file_view = 'emails.evp_confirm_approve_admin';
                    $mail->message = $volunteer->nama . ' disetujui oleh PLN Pusat sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                    $mail->status = 'CRTD';
                    $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
                    $mail->notification_id = $notif->id;
                    $mail->jenis = '4';

                    $mail->save();
                }

                return redirect('evp/approval/volunteer/' . $evp->id)->with('success', 'Terimakasih atas persetujuan yang telah Anda berikan.');
            } else {
                if ($volunteer->company_code != Auth::user()->company_code)
                    return redirect('evp/approval')->with('warning', 'Approval Admin gagal! Anda bukan Admin EVP pegawai yang bersangkutan.');
                elseif ($volunteer->approval_atasan == null)
                    return redirect('evp/approval')->with('warning', 'Approval Admin gagal! Atasan langsung pegawai belum melakukan approval.');
                elseif ($volunteer->approval_admin != null)
                    return redirect('evp/approval')->with('warning', 'Approval Admin gagal! Permohonan sudah disetujui.');
//                elseif (Auth::user()->isGM() == false)
//                    return redirect('evp/approval')->with('warning', 'Approval GM gagal! Anda bukan GM pegawai yang bersangkutan.');
                else
                    return redirect('evp/approval')->with('error', 'Error Approval! Hubungi Administrator Kantor Pusat.');
            }

        } // approve oleh GM
        elseif ($approver == '3') {
            if (Auth::user()->isGM() &&
                $volunteer->pernr_gm == Auth::user()->strukturJabatan->pernr &&
                $volunteer->approval_atasan != null &&
                $volunteer->approval_gm == null
            ) {
//            $volunteer->approval_atasan = Carbon::now();
                $volunteer->status = 'APRV-GM';
                $volunteer->save();

                $log = StatusVolunteer::log($volunteer->id, 'Permohonan Telah Disetujui oleh General Manager.', 'APRV-GM', Auth::user()->id);

                $volunteer->approval_gm = $log->created_at;
                $volunteer->save();

                // activity log
                Activity::log('Approve EVP: ' . $evp->nama_kegiatan . '; ID Volunteer: ' . $volunteer->id . '.', 'success');

                // send email to Admin Pusat
//                $atasan = StrukturJabatan::where('pernr', $volunteer->pernr_gm)->first();
                $role_pusat = Role::find(3);

                foreach ($role_pusat->users as $user) {
//                    $user = $atasan->user;
                    if ($user != null) {
                        $notif = new Notification();
                        $notif->from = Auth::user()->username2;
                        $notif->to = $user->username2;
                        $notif->user_id_from = Auth::user()->id;
                        $notif->user_id_to = $user->id;
                        $notif->subject = 'Pengajuan EVP ' . $volunteer->nama;

                        $notif->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                        $notif->url = 'evp/volunteer/' . $volunteer->id;

                        $notif->save();

                        $mail = new MailLog();
                        $mail->to = $user->email;
                        $mail->to_name = $user->name;
                        $mail->subject = '[KOMANDO] Permohonan Review ' . $volunteer->nama . ' Untuk Menjadi Relawan Kegiatan "' . $evp->nama_kegiatan . '"';
                        $mail->file_view = 'emails.evp_approval_pusat';
                        $mail->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                        $mail->status = 'CRTD';
                        $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","nama":"' . $volunteer->nama . '","nip":"' . $volunteer->nip . '","posisi":"' . $volunteer->jabatan . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
                        $mail->notification_id = $notif->id;
                        $mail->jenis = '4';

                        $mail->save();
                    }
                }

                // send email to Pegawai
                $user = $volunteer->user;
                if ($user != null) {
                    $notif = new Notification();
                    $notif->from = Auth::user()->username2;
                    $notif->to = $user->username2;
                    $notif->user_id_from = Auth::user()->id;
                    $notif->user_id_to = $user->id;
                    $notif->subject = 'Persetujuan EVP ' . $evp->nama_kegiatan;

                    $notif->message = 'Permohonan telah disetujui oleh GM untuk program "' . $evp->nama_kegiatan . '"';
                    $notif->url = 'evp/volunteer/' . $volunteer->id;

                    $notif->save();

                    $mail = new MailLog();
                    $mail->to = $user->email;
                    $mail->to_name = $user->name;
                    $mail->subject = '[KOMANDO] Permohonan Relawan Telah Disetujui oleh ' . Auth::user()->name;
                    $mail->file_view = 'emails.evp_confirm_approve_gm';
                    $mail->message = $volunteer->nama . ' disetujui oleh GM sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                    $mail->status = 'CRTD';
                    $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","gm":"' . Auth::user()->name . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
                    $mail->notification_id = $notif->id;
                    $mail->jenis = '4';

                    $mail->save();
                }

                return redirect('evp/approval/volunteer/' . $evp->id)->with('success', 'Terimakasih atas persetujuan yang telah Anda berikan.');
            } else {
                if ($volunteer->pernr_gm != Auth::user()->strukturJabatan->pernr)
                    return redirect('evp/approval')->with('warning', 'Approval GM gagal! Anda bukan GM pegawai yang bersangkutan.');
                elseif ($volunteer->approval_atasan == null)
                    return redirect('evp/approval')->with('warning', 'Approval GM gagal! Atasan langsung pegawai belum melakukan approval.');
                elseif ($volunteer->approval_gm != null)
                    return redirect('evp/approval')->with('warning', 'Approval GM gagal! Permohonan sudah disetujui.');
                elseif (Auth::user()->isGM() == false)
                    return redirect('evp/approval')->with('warning', 'Approval GM gagal! Anda bukan GM pegawai yang bersangkutan.');
                else
                    return redirect('evp/approval')->with('error', 'Error Approval GM! Hubungi Administrator.');
            }

        } // approve oleh Pusat
        elseif ($approver == '4') {
            if (Auth::user()->can('evp_approve') &&
                $volunteer->approval_atasan != null
                && $volunteer->approval_gm != null
                && $volunteer->approval_pusat == null
            ) {
//            $volunteer->approval_atasan = Carbon::now();
                $volunteer->status = 'APRV-PST';
                $volunteer->save();

                $log = StatusVolunteer::log($volunteer->id, 'Permohonan telah disetujui oleh PLN Pusat.', 'APRV-PST', Auth::user()->id);

                $volunteer->approval_pusat = $log->created_at;
                $volunteer->save();

                // activity log
                Activity::log('Approve EVP: ' . $evp->nama_kegiatan . '; ID Volunteer: ' . $volunteer->id . '.', 'success');


                // send email to Pegawai
                $user = $volunteer->user;
                if ($user != null) {
                    $notif = new Notification();
                    $notif->from = Auth::user()->username2;
                    $notif->to = $user->username2;
                    $notif->user_id_from = Auth::user()->id;
                    $notif->user_id_to = $user->id;
                    $notif->subject = 'Persetujuan EVP ' . $evp->nama_kegiatan;

                    $notif->message = 'Permohonan telah disetujui oleh PLN Pusat untuk program "' . $evp->nama_kegiatan . '"';
                    $notif->url = 'evp/volunteer/' . $volunteer->id;

                    $notif->save();

                    $mail = new MailLog();
                    $mail->to = $user->email;
                    $mail->to_name = $user->name;
                    $mail->subject = '[KOMANDO] Permohonan Relawan Telah Disetujui oleh PLN Pusat';
                    $mail->file_view = 'emails.evp_confirm_approve_pusat';
                    $mail->message = $volunteer->nama . ' disetujui oleh PLN Pusat sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                    $mail->status = 'CRTD';
                    $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
                    $mail->notification_id = $notif->id;
                    $mail->jenis = '4';

                    $mail->save();
                }

                return redirect('evp/approval/volunteer/' . $evp->id)->with('success', 'Terimakasih atas persetujuan yang telah Anda berikan.');
            } else {
                if (Auth::user()->can('evp_approve') == false)
                    return redirect('evp/approval')->with('warning', 'Approval Pusat gagal! Anda tidak memiliki otoritas untuk melakukan approval.');
                elseif ($volunteer->approval_atasan == null)
                    return redirect('evp/approval')->with('warning', 'Approval Pusat gagal! Atasan langsung pegawai belum melakukan approval.');
                elseif ($volunteer->approval_gm == null)
                    return redirect('evp/approval')->with('warning', 'Approval Pusat gagal! GM belum melakukan approval.');
                elseif ($volunteer->approval_pusat != null)
                    return redirect('evp/approval')->with('warning', 'Approval Pusat gagal! Permohonan sudah disetujui.');
                else
                    return redirect('evp/approval')->with('error', 'Error Approval Pusat! Hubungi Administrator.');
            }
        } else {
            return redirect('evp/program')->with('error', 'Error Approver! Contact your Administrator.');
        }


//        // approval oleh GM
//        if (Auth::user()->isGM() &&
//            $volunteer->pernr_gm == Auth::user()->strukturJabatan->pernr &&
//            $volunteer->approval_atasan != null &&
//            $volunteer->approval_gm == null
//        ) {
//
//            $volunteer->approval_gm = Carbon::now();
//            $volunteer->save();
//        }
//        else{
//
//        }
        // approval oleh pusat


    }

    public function rejectEVP(Request $request)
    {
//        dd($request);
        if (!(Auth::user()->can('evp_approve') || Auth::user()->isStruktural() || Auth::user()->isGM()))
            return redirect('evp/approval')->with('warning', 'You have no authorization!');

        if ($request->alasan_ditolak == null)
            return redirect('evp/approval')->with('warning', 'Alasan penolakan harus dimasukkan.');

//        dd($request);

        $approver = $request->approver;

        $volunteer = Volunteer::find($request->volunteer_id);
        $evp = $volunteer->evp;

//        dd($volunteer);

        // reject oleh atasan
        if ($approver == '1') {
            if (Auth::user()->isStruktural() &&
                $volunteer->pernr_atasan == Auth::user()->strukturJabatan->pernr &&
                $volunteer->approval_atasan == null
            ) {
//            $volunteer->approval_atasan = Carbon::now();
                $volunteer->status = 'REJ-AT';
                $volunteer->save();

                $log = StatusVolunteer::log($volunteer->id, 'Permohonan ditolak oleh atasan langsung, dengan alasan: ' . $request->alasan_ditolak, 'REJ-AT', Auth::user()->id);

//                $volunteer->approval_atasan = $log->created_at;
//                $volunteer->save();

                // activity log
                Activity::log('Reject EVP: ' . $evp->nama_kegiatan . '; ID Volunteer: ' . $volunteer->id . '.', 'success');

                // send email to Pegawai
                $user = $volunteer->user;
                if ($user != null) {
                    $notif = new Notification();
                    $notif->from = Auth::user()->username2;
                    $notif->to = $user->username2;
                    $notif->user_id_from = Auth::user()->id;
                    $notif->user_id_to = $user->id;
                    $notif->subject = 'Permohonan EVP ' . $evp->nama_kegiatan;
                    $notif->color = 'danger';
                    $notif->message = 'Permohonan ditolak oleh atasan langsung untuk program "' . $evp->nama_kegiatan . '"';
                    $notif->url = 'evp/volunteer/' . $volunteer->id;

                    $notif->save();

                    $mail = new MailLog();
                    $mail->to = $user->email;
                    $mail->to_name = $user->name;
                    $mail->subject = '[KOMANDO] Permohonan Relawan Ditolak oleh ' . Auth::user()->name;
                    $mail->file_view = 'emails.evp_confirm_reject_at';
                    $mail->message = $volunteer->nama . ' ditolak oleh atasan langsung sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                    $mail->status = 'CRTD';
                    $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","atasan":"' . Auth::user()->name . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '","alasan_ditolak":"' . $request->alasan_ditolak . '"}';
                    $mail->notification_id = $notif->id;
                    $mail->jenis = '4';

                    $mail->save();
                }

                return redirect('evp/approval-atasan')->with('success', 'Terimakasih. Data berhasil disimpan.');
            } else {
                if ($volunteer->pernr_atasan != Auth::user()->strukturJabatan->pernr)
                    return redirect('evp/approval-atasan')->with('warning', 'Approval atasan gagal! Anda bukan Atasan pegawai yang bersangkutan.');
                elseif ($volunteer->approval_atasan != null)
                    return redirect('evp/approval-atasan')->with('warning', 'Approval atasan gagal! Permohonan sudah disetujui.');
                else
                    return redirect('evp/approval-atasan')->with('error', 'Error Approval Atasan! Hubungi Administrator.');
            }
        } // reject oleh Admin EVP
        elseif ($approver == '2') {
            if (Auth::user()->hasRole('admin_evp') &&
//                $volunteer->pernr_gm == Auth::user()->strukturJabatan->pernr &&
                $volunteer->approval_atasan != null &&
                $volunteer->approval_admin_lv1 == null
            ) {
//            $volunteer->approval_atasan = Carbon::now();
                $volunteer->status = 'REJ-ADM';
                $volunteer->save();

                $log = StatusVolunteer::log($volunteer->id, 'Permohonan Ditolak oleh Admin Unit Induk, dengan alasan: ' . $request->alasan_ditolak, 'REJ-ADM', Auth::user()->id);

//                $volunteer->approval_gm = $log->created_at;
//                $volunteer->save();

                // activity log
                Activity::log('Reject EVP: ' . $evp->nama_kegiatan . '; ID Volunteer: ' . $volunteer->id . '.', 'success');


                // send email to Pegawai
                $user = $volunteer->user;
                if ($user != null) {
                    $notif = new Notification();
                    $notif->from = Auth::user()->username2;
                    $notif->to = $user->username2;
                    $notif->user_id_from = Auth::user()->id;
                    $notif->user_id_to = $user->id;
                    $notif->subject = 'Permohonan EVP ' . $evp->nama_kegiatan;
                    $notif->color = 'danger';
                    $notif->message = 'Permohonan ditolak oleh Admin Unit Induk untuk program "' . $evp->nama_kegiatan . '"';
                    $notif->url = 'evp/volunteer/' . $volunteer->id;

                    $notif->save();

                    $mail = new MailLog();
                    $mail->to = $user->email;
                    $mail->to_name = $user->name;
                    $mail->subject = '[KOMANDO] Permohonan Relawan Ditolak oleh Admin Unit Induk';
                    $mail->file_view = 'emails.evp_confirm_reject_admin';
                    $mail->message = $volunteer->nama . ' ditolak oleh Admin Unit Induk sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                    $mail->status = 'CRTD';
                    $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","gm":"' . Auth::user()->name . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '","alasan_ditolak":"' . $request->alasan_ditolak . '"}';
                    $mail->notification_id = $notif->id;
                    $mail->jenis = '4';

                    $mail->save();
                }

                return redirect('evp/approval/volunteer/' . $evp->id)->with('success', 'Terimakasih. Data berhasil disimpan.');
            } else {
                if ($volunteer->company_code != Auth::user()->company_code)
                    return redirect('evp/approval')->with('warning', 'Approval Admin gagal! Anda bukan Admin EVP pegawai yang bersangkutan.');
                elseif ($volunteer->approval_atasan == null)
                    return redirect('evp/approval')->with('warning', 'Approval Admin gagal! Atasan langsung pegawai belum melakukan approval.');
                elseif ($volunteer->approval_admin != null)
                    return redirect('evp/approval')->with('warning', 'Approval Admin gagal! Permohonan sudah disetujui.');
//                elseif (Auth::user()->isGM() == false)
//                    return redirect('evp/approval')->with('warning', 'Approval GM gagal! Anda bukan GM pegawai yang bersangkutan.');
                else
                    return redirect('evp/approval')->with('error', 'Error Approval! Hubungi Administrator Kantor Pusat.');
            }

        } // reject oleh GM
        elseif ($approver == '3') {
            if (Auth::user()->isGM() &&
                $volunteer->pernr_gm == Auth::user()->strukturJabatan->pernr &&
                $volunteer->approval_atasan != null &&
                $volunteer->approval_gm == null
            ) {
//            $volunteer->approval_atasan = Carbon::now();
                $volunteer->status = 'REJ-GM';
                $volunteer->save();

                $log = StatusVolunteer::log($volunteer->id, 'Permohonan Ditolak oleh General Manager, dengan alasan: ' . $request->alasan_ditolak, 'REJ-GM', Auth::user()->id);

//                $volunteer->approval_gm = $log->created_at;
//                $volunteer->save();

                // activity log
                Activity::log('Reject EVP: ' . $evp->nama_kegiatan . '; ID Volunteer: ' . $volunteer->id . '.', 'success');


                // send email to Pegawai
                $user = $volunteer->user;
                if ($user != null) {
                    $notif = new Notification();
                    $notif->from = Auth::user()->username2;
                    $notif->to = $user->username2;
                    $notif->user_id_from = Auth::user()->id;
                    $notif->user_id_to = $user->id;
                    $notif->subject = 'Permohonan EVP ' . $evp->nama_kegiatan;
                    $notif->color = 'danger';
                    $notif->message = 'Permohonan ditolak oleh GM untuk program "' . $evp->nama_kegiatan . '"';
                    $notif->url = 'evp/volunteer/' . $volunteer->id;

                    $notif->save();

                    $mail = new MailLog();
                    $mail->to = $user->email;
                    $mail->to_name = $user->name;
                    $mail->subject = '[KOMANDO] Permohonan Relawan Ditolak oleh ' . Auth::user()->name;
                    $mail->file_view = 'emails.evp_confirm_reject_gm';
                    $mail->message = $volunteer->nama . ' ditolak oleh GM sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                    $mail->status = 'CRTD';
                    $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","gm":"' . Auth::user()->name . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '","alasan_ditolak":"' . $request->alasan_ditolak . '"}';
                    $mail->notification_id = $notif->id;
                    $mail->jenis = '4';

                    $mail->save();
                }

                return redirect('evp/approval/volunteer/' . $evp->id)->with('success', 'Terimakasih. Data berhasil disimpan.');
            } else {
                if ($volunteer->pernr_gm != Auth::user()->strukturJabatan->pernr)
                    return redirect('evp/approval')->with('warning', 'Approval GM gagal! Anda bukan GM pegawai yang bersangkutan.');
                elseif ($volunteer->approval_atasan == null)
                    return redirect('evp/approval')->with('warning', 'Approval GM gagal! Atasan langsung pegawai belum melakukan approval.');
                elseif ($volunteer->approval_gm != null)
                    return redirect('evp/approval')->with('warning', 'Approval GM gagal! Permohonan sudah disetujui.');
                elseif (Auth::user()->isGM() == false)
                    return redirect('evp/approval')->with('warning', 'Approval GM gagal! Anda bukan GM pegawai yang bersangkutan.');
                else
                    return redirect('evp/approval')->with('error', 'Error Approval GM! Hubungi Administrator.');
            }

        } // reject oleh Pusat
        elseif ($approver == '4') {
            if (Auth::user()->can('evp_approve') &&
                $volunteer->approval_atasan != null
                && $volunteer->approval_gm != null
                && $volunteer->approval_pusat == null
            ) {
//            $volunteer->approval_atasan = Carbon::now();
                $volunteer->status = 'REJ-PST';
                $volunteer->save();

                $log = StatusVolunteer::log($volunteer->id, 'Permohonan ditolak oleh PLN Pusat, dengan alasan: ' . $request->alasan_ditolak, 'REJ-PST', Auth::user()->id);

//                $volunteer->approval_pusat = $log->created_at;
//                $volunteer->save();

                // activity log
                Activity::log('Reject EVP: ' . $evp->nama_kegiatan . '; ID Volunteer: ' . $volunteer->id . '.', 'success');


                // send email to Pegawai
                $user = $volunteer->user;
                if ($user != null) {
                    $notif = new Notification();
                    $notif->from = Auth::user()->username2;
                    $notif->to = $user->username2;
                    $notif->user_id_from = Auth::user()->id;
                    $notif->user_id_to = $user->id;
                    $notif->subject = 'Permohonan EVP ' . $evp->nama_kegiatan;
                    $notif->color = 'danger';
                    $notif->message = 'Permohonan ditolak oleh PLN Pusat untuk program "' . $evp->nama_kegiatan . '"';
                    $notif->url = 'evp/volunteer/' . $volunteer->id;

                    $notif->save();

                    $mail = new MailLog();
                    $mail->to = $user->email;
                    $mail->to_name = $user->name;
                    $mail->subject = '[KOMANDO] Permohonan Relawan Ditolak oleh PLN Pusat';
                    $mail->file_view = 'emails.evp_confirm_reject_pusat';
                    $mail->message = $volunteer->nama . ' ditolak oleh PLN Pusat sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                    $mail->status = 'CRTD';
                    $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '","alasan_ditolak":"' . $request->alasan_ditolak . '"}';
                    $mail->notification_id = $notif->id;
                    $mail->jenis = '4';

                    $mail->save();
                }

                return redirect('evp/approval/volunteer/' . $evp->id)->with('success', 'Terimakasih. Data berhasil disimpan.');
            } else {
                if (Auth::user()->can('evp_approve') == false)
                    return redirect('evp/approval')->with('warning', 'Approval Pusat gagal! Anda tidak memiliki otoritas untuk melakukan approval.');
                elseif ($volunteer->approval_atasan == null)
                    return redirect('evp/approval')->with('warning', 'Approval Pusat gagal! Atasan langsung pegawai belum melakukan approval.');
                elseif ($volunteer->approval_gm == null)
                    return redirect('evp/approval')->with('warning', 'Approval Pusat gagal! GM belum melakukan approval.');
                elseif ($volunteer->approval_pusat != null)
                    return redirect('evp/approval')->with('warning', 'Approval Pusat gagal! Permohonan sudah disetujui.');
                else
                    return redirect('evp/approval')->with('error', 'Error Approval Pusat! Hubungi Administrator.');
            }
        } else {
            return redirect('evp/program')->with('error', 'Error Approver! Contact your Administrator.');
        }
    }

    public function approvalEVPIndex($id)
    {
        $evp = EVP::find($id);
//        $jenis_waktu_list = JenisWaktuEVP::lists('description', 'id');
//        if($evp->jenis_evp_id == 1) {
//            $volunteer_list = $evp->volunteers()->where('status', 'APRV-PST')->get();
//        }
//        else{
//            $volunteer_list = $evp->volunteers()->where('status', 'APRV-AT')->get();
//        }
        if (Auth::user()->isGM()) {
            $volunteer_list = $evp->volunteers()->where('company_code', Auth::user()->company_code)->whereIn('status', ['APRV-ADM'])->get();
        } elseif (Auth::user()->hasRole('admin_pusat')) {
            $volunteer_list = $evp->volunteers()->whereIn('status', ['APRV-GM'])->get();
        } else {
            $volunteer_list = $evp->volunteers()->where('company_code', Auth::user()->company_code)->get();
//            $volunteer_list = $evp->volunteers;
        }
        return view('evp.approval_volunteer', compact('evp', 'volunteer_list'));
    }

    public function sendNotifToGM($id)
    {
//        dd($id);
        $evp = EVP::find($id);

        if ($evp->reg_gm == '1') {

            $gm = Auth::user()->getStrukjabGM();

//            dd($gm);

            $user = $gm->user;
//            dd($user);

            if ($user != null) {
                $notif = new Notification();
                $notif->from = Auth::user()->username2;
                $notif->to = $user->username2;
                $notif->user_id_from = Auth::user()->id;
                $notif->user_id_to = $user->id;
                $notif->subject = 'Permohonan Persetujuan Employee Volunteer Program : ' . $evp->nama_kegiatan;

                $notif->message = 'Permohonan persetujuan relawan untuk program "' . $evp->nama_kegiatan . '"';
                $notif->url = 'evp/approval/volunteer/'.$evp->id;

                $notif->save();

                $mail = new MailLog();
                $mail->to = $user->email;
                $mail->to_name = $user->name;
                $mail->subject = '[KOMANDO] Permohonan Persetujuan Employee Volunteer Program : "' . $evp->nama_kegiatan . '"';
                $mail->file_view = 'emails.evp_notifikasi_gm';
                $mail->message = 'Permohonan persetujuan relawan untuk program "' . $evp->nama_kegiatan . '"';
                $mail->status = 'CRTD';
                $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
                $mail->notification_id = $notif->id;
                $mail->jenis = '4';

                $mail->save();
                return redirect('evp/approval/volunteer/'.$evp->id)->with('success', 'Notifikasi berhasil dikirimkan ke GM');
            }
            else{
                return redirect('evp/approval/volunteer/'.$evp->id)->with('warning', 'Data GM tidak ditemukan pada sistem. Mohon untuk login kedalam aplikasi KOMANDO terlebih dahulu.');
            }
        }
    }

    public function approveAllGM($id){
        $evp = EVP::find($id);
        $volunteer_list = $evp->volunteers()->where('company_code', Auth::user()->company_code)->whereIn('status', ['APRV-ADM'])->get();
//        dd($volunteer_list);
        foreach ($volunteer_list as $volunteer) {
            $volunteer->status = 'APRV-GM';
            $volunteer->save();

            $log = StatusVolunteer::log($volunteer->id, 'Permohonan Telah Disetujui oleh General Manager.', 'APRV-GM', Auth::user()->id);

            $volunteer->approval_gm = $log->created_at;
            $volunteer->save();

            // send email to Admin Pusat
            $role_pusat = Role::find(3);

            foreach ($role_pusat->users as $user) {
                if ($user != null) {
                    $notif = new Notification();
                    $notif->from = Auth::user()->username2;
                    $notif->to = $user->username2;
                    $notif->user_id_from = Auth::user()->id;
                    $notif->user_id_to = $user->id;
                    $notif->subject = 'Pengajuan EVP ' . $volunteer->nama;

                    $notif->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                    $notif->url = 'evp/volunteer/' . $volunteer->id;

                    $notif->save();

                    $mail = new MailLog();
                    $mail->to = $user->email;
                    $mail->to_name = $user->name;
                    $mail->subject = '[KOMANDO] Permohonan Review ' . $volunteer->nama . ' Untuk Menjadi Relawan Kegiatan "' . $evp->nama_kegiatan . '"';
                    $mail->file_view = 'emails.evp_approval_pusat';
                    $mail->message = $volunteer->nama . ' mengajukan sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                    $mail->status = 'CRTD';
                    $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","nama":"' . $volunteer->nama . '","nip":"' . $volunteer->nip . '","posisi":"' . $volunteer->jabatan . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
                    $mail->notification_id = $notif->id;
                    $mail->jenis = '4';

                    $mail->save();
                }
            }

            // send email to Pegawai
            $user = $volunteer->user;
            if ($user != null) {
                $notif = new Notification();
                $notif->from = Auth::user()->username2;
                $notif->to = $user->username2;
                $notif->user_id_from = Auth::user()->id;
                $notif->user_id_to = $user->id;
                $notif->subject = 'Persetujuan EVP ' . $evp->nama_kegiatan;

                $notif->message = 'Permohonan telah disetujui oleh GM untuk program "' . $evp->nama_kegiatan . '"';
                $notif->url = 'evp/volunteer/' . $volunteer->id;

                $notif->save();

                $mail = new MailLog();
                $mail->to = $user->email;
                $mail->to_name = $user->name;
                $mail->subject = '[KOMANDO] Permohonan Relawan Telah Disetujui oleh ' . Auth::user()->name;
                $mail->file_view = 'emails.evp_confirm_approve_gm';
                $mail->message = $volunteer->nama . ' disetujui oleh GM sebagai relawan untuk program "' . $evp->nama_kegiatan . '"';
                $mail->status = 'CRTD';
                $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","gm":"' . Auth::user()->name . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
                $mail->notification_id = $notif->id;
                $mail->jenis = '4';

                $mail->save();
            }

        }

        // activity log
        Activity::log('Approve All Volunteers EVP: ' . $evp->nama_kegiatan . '.', 'success');

        return redirect('evp/approval/volunteer/' . $evp->id)->with('success', 'Terimakasih atas persetujuan yang telah Anda berikan.');

//        return $id;
    }

    public function updateStatusVolunteer(){
        $now = Carbon::now();

        // EVP Lokal

        // Get Daftar EVP
        $evp_lokal = EVP::where('jenis_evp_id', '2')->get();
        foreach ($evp_lokal as $evp) {

            // update status briefing
            if($evp->briefing=='1'){
                // jika tanggal briefing != null
                if($evp->tgl_jam_briefing!=null) {
                    // jika tanggal briefing == now
                    if ($evp->tgl_jam_briefing->format('Ymd') == $now->format('Ymd')) {
                        // update status volunteer yang sudah di-approve admin
                        $volunteer_list = $evp->volunteers()->where('status', 'APRV-ADM')->get();
                        foreach ($volunteer_list as $volunteer) {
                            $volunteer->briefing = Carbon::now();
                            $volunteer->status = 'BRFG';
                            $volunteer->save();
                        }
                    }
                }
            }

            // update status aktif
            // jika tanggal briefing != null
            if($evp->waktu_awal!=null) {
                // jika tanggal briefing == now
                if ($evp->waktu_awal->format('Ymd') == $now->format('Ymd')) {

                    // jika ada briefing
                    if($evp->briefing=='1'){
                        // update status volunteer yang sudah melakukan briefing
                        $volunteer_list = $evp->volunteers()->where('status', 'BRFG')->get();
                        foreach ($volunteer_list as $volunteer) {
                            $volunteer->aktif = Carbon::now();
                            $volunteer->status = 'ACTV';
                            $volunteer->save();
                            StatusVolunteer::log($volunteer->id, $volunteer->nama.' mulai aktif menjadi relawan.', 'ACTV');
                        }
                    }
                    // jika tidak ada briefing
                    else{
                        // update status volunteer yang sudah di-approve admin
                        $volunteer_list = $evp->volunteers()->where('status', 'APRV-ADM')->get();
                        foreach ($volunteer_list as $volunteer) {
                            $volunteer->aktif = Carbon::now();
                            $volunteer->status = 'ACTV';
                            $volunteer->save();
                            StatusVolunteer::log($volunteer->id, $volunteer->nama.' mulai aktif menjadi relawan.', 'ACTV');
                        }
                    }


                }
            }
        }


        // EVP Nasional

        $evp_nasional = EVP::where('jenis_evp_id', '1')->get();

        foreach ($evp_nasional as $evp) {

            // update status briefing
            if($evp->briefing=='1'){
                // jika tanggal briefing != null
                if($evp->tgl_jam_briefing!=null) {
                    // jika tanggal briefing == now
                    if ($evp->tgl_jam_briefing->format('Ymd') == $now->format('Ymd')) {
//                        dd($evp);
                        // update status volunteer yang sudah di-approve admin pusat
                        $volunteer_list = $evp->volunteers()->where('status', 'APRV-PST')->get();
//                        dd($volunteer_list);
                        foreach ($volunteer_list as $volunteer) {
                            $volunteer->briefing = Carbon::now();
                            $volunteer->status = 'BRFG';
                            $volunteer->save();
                        }
                    }
                }
            }

            // update status aktif
            // jika tanggal briefing != null
            if($evp->waktu_awal!=null) {
                // jika tanggal briefing == now
                if ($evp->waktu_awal->format('Ymd') == $now->format('Ymd')) {

                    // jika ada briefing
                    if($evp->briefing=='1'){
                        // update status volunteer yang sudah melakukan briefing
                        $volunteer_list = $evp->volunteers()->where('status', 'BRFG')->get();
                        foreach ($volunteer_list as $volunteer) {
                            $volunteer->aktif = Carbon::now();
                            $volunteer->status = 'ACTV';
                            $volunteer->save();
                            StatusVolunteer::log($volunteer->id, $volunteer->nama.' mulai aktif menjadi relawan.', 'ACTV');
                        }
                    }
                    // jika tidak ada briefing
                    else{
                        // update status volunteer yang sudah di-approve admin
                        $volunteer_list = $evp->volunteers()->where('status', 'APRV-PST')->get();
                        foreach ($volunteer_list as $volunteer) {
                            $volunteer->aktif = Carbon::now();
                            $volunteer->status = 'ACTV';
                            $volunteer->save();
                            StatusVolunteer::log($volunteer->id, $volunteer->nama.' mulai aktif menjadi relawan.', 'ACTV');
                        }
                    }


                }
            }
        }

        return 'FINISH';
    }

    public function storeTestimoni(Request $request){
        if($request->testimoni==null || $request->testimoni==''){
            return redirect('evp/dashboard')->with('error','Testimoni wajib diisi.');
        }

        if(strlen($request->testimoni)<30){
            return redirect('evp/dashboard')->with('error','Testimoni minimal 30 karakter.');
        }

//        dd($request);

        $volunteer = Volunteer::find($request->volunteer_id);

        $volunteer->testimoni = $request->testimoni;
        $volunteer->status = 'COMP';
        $volunteer->finish = Carbon::now();
        $volunteer->save();

        StatusVolunteer::log($volunteer->id, $volunteer->nama.' telah selesai menjadi relawan.', 'COMP');

        Activity::log('Create testimoni EVP : '.$volunteer->evp->nama_kegiatan.'; ID: '.$volunteer->id, 'success');

        return redirect('evp/dashboard')->with('success', 'Terimakasih atas testimoni yang diberikan.');
    }
}
