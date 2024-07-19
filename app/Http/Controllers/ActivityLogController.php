<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ActivityLog;
use App\EVP;
use App\Http\Requests\LogRequest;
use App\MailLog;
use App\Notification;
use App\StrukturJabatan;
use App\Volunteer;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //validasi use role
        if(!(Auth::user()->hasRole('root') ||Auth::user()->hasRole('admin_pusat') ||Auth::user()->hasRole('admin_evp') || Auth::user()->isStruktural())){
            return redirect('/')->with('error', 'You are not authorized');
        }

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
            // get volunteer
            $arr_evp = Volunteer::where('pernr_atasan',Auth::user()->pa0032->pernr)->orderBy('evp_id', 'asc')->pluck('evp_id')->unique()->toArray();
//            dd($arr_evp);
            // get evp list
            $evp_list = EVP::where('status', 'ACTV')
                ->whereIn('id', $arr_evp)
                ->whereHas('businessArea', function($q)
                {
                    $q->whereId(Auth::user()->businessArea->id);
                })
                ->orderBy('id', 'desc')->get();
//            dd($evp_list);
        }
//        dd($evp_list);

        return view('evp.log_program_list', compact('evp_list'));
    }

    public function volunteerIndex($id){
        $evp = EVP::find($id);
//        $jenis_waktu_list = JenisWaktuEVP::lists('description', 'id');
//        if($evp->jenis_evp_id == 1) {
//            $volunteer_list = $evp->volunteers()->where('status', 'APRV-PST')->get();
//        }
//        else{
//            $volunteer_list = $evp->volunteers()->where('status', 'APRV-AT')->get();
//        }
        if (Auth::user()->isGM() || Auth::user()->hasRole('admin_evp')) {
            $volunteer_list = $evp->volunteers()->whereIn('status', ['BRFG','ACTV','COMP'])->where('company_code', Auth::user()->company_code)->get();
        } elseif (Auth::user()->hasRole('admin_pusat')) {
            $volunteer_list = $evp->volunteers()->whereIn('status', ['BRFG','ACTV','COMP'])->get();
        } elseif (Auth::user()->isStruktural()) {
            $volunteer_list = $evp->volunteers()->whereIn('status', ['BRFG','ACTV','COMP'])->where('company_code', Auth::user()->company_code)->where('pernr_atasan',Auth::user()->pa0032->pernr)->get();
//            dd($volunteer_list);
        } else {
            $volunteer_list = $evp->volunteers()->whereIn('status', ['BRFG','ACTV','COMP'])->where('company_code', Auth::user()->company_code)->get();
//            $volunteer_list = $evp->volunteers;
        }
        return view('evp.log_volunteer', compact('evp', 'volunteer_list'));
    }

    public function logIndex($id){
        // cek pegawai ybs atau admin evp / admin pusat / root / atasan
        $volunteer = Volunteer::find($id);

        //validasi use role
        if(!(Auth::user()->hasRole('root') ||
            Auth::user()->hasRole('admin_pusat') ||
            Auth::user()->hasRole('admin_evp') ||
            $volunteer->pernr_atasan==Auth::user()->pa0032->pernr ||
            $volunteer->nip==Auth::user()->nip
        )){
            return redirect('/')->with('error', 'You are not authorized');
        }

        $evp = $volunteer->evp;

        if(Auth::user()->id!=$volunteer->user_id &&
            !(Auth::user()->hasRole('root')||
                Auth::user()->hasRole('admin_evp')||
                Auth::user()->hasRole('admin_pusat'||
                (Auth::user()->isStruktural() && $volunteer->pernr_atasan == Auth::user()->strukturJabatan->pernr)))){
            return redirect('/')->with('error', 'You are not authorized');
        }

        return view('evp.log_volunteer_list', compact('evp','volunteer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($id)
    {
//        dd($id);
        $volunteer = Volunteer::find($id);
        $evp = $volunteer->evp;

        if(Auth::user()->id!=$volunteer->user_id && !(Auth::user()->hasRole('root')||Auth::user()->hasRole('admin_evp')||Auth::user()->hasRole('admin_pusat'))){
            return redirect('/')->with('error', 'You are not authorized');
        }

//        $atasan = Auth::user()->getStrukjabAtasan();
//        $pernr_atasan = $atasan->pernr;
//
//        $gm = Auth::user()->getStrukjabGM();

        return view('evp.log_create', compact('volunteer', 'evp'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($id, LogRequest $request)
    {
//        dd($request);
        $volunteer = Volunteer::find($id);

        if(Auth::user()->id!=$volunteer->user_id && !(Auth::user()->hasRole('root')||Auth::user()->hasRole('admin_evp')||Auth::user()->hasRole('admin_pusat'))){
            return redirect('/')->with('error', 'You are not authorized');
        }

        $log = new ActivityLog();
        $log->evp_id = $volunteer->evp_id;
        $log->volunteer_id = $volunteer->id;
        $log->waktu = Carbon::parse($request->tanggal.' '.$request->jam);
        $log->lokasi = $request->lokasi;
        $log->aktivitas = $request->aktivitas;

        $foto_1 = $request->file('foto_1');
        $foto_2 = $request->file('foto_2');
        $foto_3 = $request->file('foto_3');

        if ($foto_1 != null) {
            $filename = date('Ymdhis').'_'.$foto_1->getClientOriginalName();
            Storage::put('evp/activity/' . $filename, File::get($foto_1));

            $img = Image::make(File::get($foto_1));
            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            // save file as jpg with medium quality
            $img->save(storage_path('app/evp/activity-thumb/' . $filename));
            $log->foto_1 = $filename;
        }

        if ($foto_2 != null) {
            $filename = date('Ymdhis').'_'.$foto_2->getClientOriginalName();
            Storage::put('evp/activity/' . $filename, File::get($foto_2));

            $img = Image::make(File::get($foto_2));
            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            // save file as jpg with medium quality
            $img->save(storage_path('app/evp/activity-thumb/' . $filename));
            $log->foto_2 = $filename;
        }

        if ($foto_3 != null) {
            $filename = date('Ymdhis').'_'.$foto_3->getClientOriginalName();
            Storage::put('evp/activity/' . $filename, File::get($foto_3));

            $img = Image::make(File::get($foto_3));
            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            // save file as jpg with medium quality
            $img->save(storage_path('app/evp/activity-thumb/' . $filename));
            $log->foto_3 = $filename;
        }

//        dd($log);
        $log->save();

        // send notif to atasan langsung
        $atasan = StrukturJabatan::where('pernr', $volunteer->pernr_atasan)->first();
        $evp = $volunteer->evp;

        $user = $atasan->user;
        if ($user != null) {
//        $user = User::find();
            $notif = new Notification();
            $notif->from = Auth::user()->username2;
            $notif->to = $user->username2;
            $notif->user_id_from = Auth::user()->id;
            $notif->user_id_to = $user->id;
            $notif->subject = 'Persetujuan Activity Log';
//            $notif->color = 'pink';
//            $notif->icon = 'fa fa-heart-o';

            $notif->message = $volunteer->nama . ' telah melakukan aktivitas untuk program "' . $evp->nama_kegiatan . '"';
            $notif->url = 'evp/log/list/' . $volunteer->id;

            $notif->save();


            $mail = new MailLog();
            $mail->to = $user->email;
            $mail->to_name = $user->name;
            $mail->subject = '[KOMANDO] Permohonan Persetujuan Activity Log ' . $volunteer->nama . ' pada Kegiatan "' . $evp->nama_kegiatan . '"';
            $mail->file_view = 'emails.log_approval';
            $mail->message = $volunteer->nama . ' telah melakukan aktivitas untuk program "' . $evp->nama_kegiatan . '"';
            $mail->status = 'CRTD';
            $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","nama":"' . $volunteer->nama . '","nip":"' . $volunteer->nip . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
            $mail->notification_id = $notif->id;
            $mail->jenis = '4';

            $mail->save();
        }

        Activity::log('Create activity log EVP : '.$volunteer->evp->nama_kegiatan.' ('.$log->waktu->format('d/m/Y H:i').'); ID: '.$log->id, 'success');

        return redirect('evp/log/list/' . $volunteer->id)->with('success', 'Activity Log berhasil disimpan.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $log = ActivityLog::find($id);
        $volunteer = $log->volunteer;

        if(Auth::user()->id!=$volunteer->user_id && !(Auth::user()->hasRole('root')||Auth::user()->hasRole('admin_evp')||Auth::user()->hasRole('admin_pusat'))){
            return redirect('/')->with('error', 'You are not authorized');
        }

        $evp = $volunteer->evp;

        return view('evp.log_edit', compact('volunteer', 'evp', 'log'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, LogRequest $request)
    {
//        dd($request);
        $log = ActivityLog::find($id);
        $volunteer = $log->volunteer;

        if(Auth::user()->id!=$volunteer->user_id && !(Auth::user()->hasRole('root')||Auth::user()->hasRole('admin_evp')||Auth::user()->hasRole('admin_pusat'))){
            return redirect('/')->with('error', 'You are not authorized');
        }

//        $log = new ActivityLog();
        $log->evp_id = $volunteer->evp_id;
//        $log->volunteer_id = $volunteer->id;
        $log->waktu = Carbon::parse($request->tanggal.' '.$request->jam);
        $log->lokasi = $request->lokasi;
        $log->aktivitas = $request->aktivitas;

        $foto_1 = $request->file('foto_1');
        $foto_2 = $request->file('foto_2');
        $foto_3 = $request->file('foto_3');

        if ($foto_1 != null) {
            $filename = date('Ymdhis').'_'.$foto_1->getClientOriginalName();
            Storage::put('evp/activity/' . $filename, File::get($foto_1));

            $img = Image::make(File::get($foto_1));
            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            // save file as jpg with medium quality
            $img->save(storage_path('app/evp/activity-thumb/' . $filename));
            $log->foto_1 = $filename;
        }

        if ($foto_2 != null) {
            $filename = date('Ymdhis').'_'.$foto_2->getClientOriginalName();
            Storage::put('evp/activity/' . $filename, File::get($foto_2));

            $img = Image::make(File::get($foto_2));
            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            // save file as jpg with medium quality
            $img->save(storage_path('app/evp/activity-thumb/' . $filename));
            $log->foto_2 = $filename;
        }

        if ($foto_3 != null) {
            $filename = date('Ymdhis').'_'.$foto_3->getClientOriginalName();
            Storage::put('evp/activity/' . $filename, File::get($foto_3));

            $img = Image::make(File::get($foto_3));
            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            // save file as jpg with medium quality
            $img->save(storage_path('app/evp/activity-thumb/' . $filename));
            $log->foto_3 = $filename;
        }

//        dd($log);
        $log->save();

        Activity::log('Edit activity log EVP : '.$volunteer->evp->nama_kegiatan.' ('.$log->waktu->format('d/m/Y H:i').'); ID: '.$log->id, 'success');

        return redirect('evp/log/list/' . $volunteer->id)->with('success', 'Activity Log berhasil diubah.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {

    }

    public function approveLog($id){
        $log = ActivityLog::find($id);
        $volunteer = $log->volunteer;
//        dd($volunteer->pernr_atasan);

        //validasi use role
        if(!(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat') ||Auth::user()->hasRole('admin_evp') || Auth::user()->pa0032->pernr==$volunteer->pernr_atasan)){
            return redirect('/')->with('error', 'You are not authorized');
        }

        $log->status = 'APRV';
        $log->save();

        // notifikasi ke relawan
        $evp = $volunteer->evp;

        $user = $volunteer->user;
        if ($user != null) {
//        $user = User::find();
            $notif = new Notification();
            $notif->from = Auth::user()->username2;
            $notif->to = $user->username2;
            $notif->user_id_from = Auth::user()->id;
            $notif->user_id_to = $user->id;
            $notif->subject = 'Activity Log Approved';
//            $notif->color = 'pink';
//            $notif->icon = 'fa fa-heart-o';

            $notif->message = 'Aktivitas kegiatan "' . $evp->nama_kegiatan . '" telah disetujui oleh atasan';
            $notif->url = 'evp/log/list/' . $volunteer->id;

            $notif->save();

            $mail = new MailLog();
            $mail->to = $user->email;
            $mail->to_name = $user->name;
            $mail->subject = '[KOMANDO] Activity Log ' . $volunteer->nama . ' pada Kegiatan "' . $evp->nama_kegiatan . '" Telah Disetujui';
            $mail->file_view = 'emails.evp_confirm_approve_log';
            $mail->message = 'Aktivitas kegiatan "' . $evp->nama_kegiatan . '" telah disetujui oleh atasan';
            $mail->status = 'CRTD';
            $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","nama":"' . $volunteer->nama . '","nip":"' . $volunteer->nip . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
            $mail->notification_id = $notif->id;
            $mail->jenis = '4';

            $mail->save();
        }


        Activity::log('Approve activity log EVP : '.$volunteer->nama.' ('.$log->waktu->format('d/m/Y H:i').'); ID: '.$log->id, 'success');

        return redirect('evp/log/list/' . $volunteer->id)->with('success', 'Terimakasih atas persetujuan yang diberikan.');

    }

    public function approveAllLog($id){
        $volunteer = Volunteer::find($id);
//        dd($volunteer->pernr_atasan);

        //validasi use role
        if(!(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat') ||Auth::user()->hasRole('admin_evp') || Auth::user()->pa0032->pernr==$volunteer->pernr_atasan)){
            return redirect('/')->with('error', 'You are not authorized');
        }

        foreach ($volunteer->activityLog as $log) {
            $log->status = 'APRV';
            $log->save();
        }

        // notifikasi ke relawan
        $evp = $volunteer->evp;

        $user = $volunteer->user;
        if ($user != null) {
//        $user = User::find();
            $notif = new Notification();
            $notif->from = Auth::user()->username2;
            $notif->to = $user->username2;
            $notif->user_id_from = Auth::user()->id;
            $notif->user_id_to = $user->id;
            $notif->subject = 'Activity Log Approved';
//            $notif->color = 'pink';
//            $notif->icon = 'fa fa-heart-o';

            $notif->message = 'Aktivitas kegiatan "' . $evp->nama_kegiatan . '" telah disetujui oleh atasan';
            $notif->url = 'evp/log/list/' . $volunteer->id;

            $notif->save();

            $mail = new MailLog();
            $mail->to = $user->email;
            $mail->to_name = $user->name;
            $mail->subject = '[KOMANDO] Activity Log ' . $volunteer->nama . ' pada Kegiatan "' . $evp->nama_kegiatan . '" Telah Disetujui';
            $mail->file_view = 'emails.evp_confirm_approve_log';
            $mail->message = 'Aktivitas kegiatan "' . $evp->nama_kegiatan . '" telah disetujui oleh atasan';
            $mail->status = 'CRTD';
            $mail->parameter = '{"id_evp":"' . $evp->id . '","foto":"' . $evp->foto . '","nama_kegiatan":"' . $evp->nama_kegiatan . '","nama":"' . $volunteer->nama . '","nip":"' . $volunteer->nip . '","lokasi":"' . $evp->tempat . '","waktu":"' . $evp->waktu_awal->format('d M Y') . ' - ' . $evp->waktu_akhir->format('d M Y') . '"}';
            $mail->notification_id = $notif->id;
            $mail->jenis = '4';

            $mail->save();
        }


        Activity::log('Approve activity log EVP : '.$volunteer->nama.' ('.$log->waktu->format('d/m/Y H:i').'); ID: '.$log->id, 'success');

        return redirect('evp/log/list/' . $volunteer->id)->with('success', 'Terimakasih atas persetujuan yang diberikan.');

    }
}
