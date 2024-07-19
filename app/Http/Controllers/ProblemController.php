<?php

namespace App\Http\Controllers;

use App\Activity;
use App\BusinessArea;
use App\CompanyCode;
use App\Http\Requests\ProblemRequest;
use App\Mail;
use App\MailLog;
use App\Notification;
use App\Problem;
use App\ProblemGroup;
use App\ProblemStatus;
use App\Role;
use App\Services\Datatable;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Utils\CompanyCodeUtil;
use App\Utils\UnitKerjaUtil;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();
        $cc_selected = request('company_code');
        $companyCode = [];

        if (empty($cc_selected)) {
            $companyCode = (new UnitKerjaUtil)->shiftingCompanyCode($user);
            $cc_selected = $companyCode[0];
        }

        $ccList = ['Select Unit'] + (new CompanyCodeUtil)->generateOptions($user, $companyCode, false);

        $company_code = CompanyCode::where('company_code', $cc_selected)->first();

        if ($user->hasRole('root')) {
            $query = Problem::where('status', '!=', 4)
                ->whereYear('created_at', '=', date('Y'));

            $jml_on_progress = Problem::where('status', 1)
                ->whereYear('created_at', '=', date('Y'))
                ->orderBy('id', 'desc')
                ->get()
                ->count();
            $jml_wait = Problem::where('status', 2)
                ->whereYear('created_at', '=', date('Y'))
                ->orderBy('id', 'desc')
                ->get()
                ->count();
            $jml_resolved = Problem::where('status', 3)
                ->whereYear('created_at', '=', date('Y'))
                ->orderBy('id', 'desc')
                ->get()
                ->count();
            $jml_closed = Problem::where('status', 4)
                ->whereYear('created_at', '=', date('Y'))
                ->orderBy('id', 'desc')
                ->get()
                ->count();
        } else {
            $query = $company_code->problem();

            $jml_on_progress = $company_code->problem()->where('status', 1)->get()->count();
            $jml_wait = $company_code->problem()->where('status', 2)->get()->count();
            $jml_resolved = $company_code->problem()->where('status', 3)->get()->count();
            $jml_closed = $company_code->problem()->where('status', 4)->get()->count();
        }

        $arr_jml = [$jml_on_progress, $jml_wait, $jml_resolved, $jml_closed];

        $query->with('caseOwner', 'group', 'server');

        $datatable = Datatable::make($query)
            ->rowView('problem.problem_list_row')
            ->search(function ($builder, $keyword) {
                $keyword = strtolower($keyword);
                $builder->where(function ($q) use ($keyword) {
                    $q->where(function ($q2) use ($keyword) {
                        $q2->where(DB::raw('lower(id)'), 'like', "%$keyword%")
                            ->orWhere(DB::raw('lower(unit)'), 'like', "%$keyword%")
                            ->orWhere(DB::raw('lower(deskripsi)'), 'like', "%$keyword%")
                            ->orWhereHas('caseOwner', function ($q3) use ($keyword) {
                                $q3->orWhere(DB::raw('lower(name)'), 'like', "%$keyword%");
                                $q3->orWhere(DB::raw('lower(nip)'), 'like', "%$keyword%");
                            });
                    });
                });
            })
            ->columns([
                ['data' => 'id', 'searchable' => false, 'sortable' => true],
                ['data' => 'created_at', 'searchable' => false, 'sortable' => true],
                ['data' => 'owner', 'searchable' => false, 'sortable' => false],
                ['data' => 'unit', 'searchable' => false, 'sortable' => true],
                ['data' => 'server', 'searchable' => false, 'sortable' => false],
                ['data' => 'grup', 'searchable' => false, 'sortable' => false],
                ['data' => 'deskripsi', 'searchable' => false, 'sortable' => true],
                ['data' => 'status', 'searchable' => false, 'sortable' => false],
            ]);

        if (request()->wantsJson()) {
            return $datatable->toJson();
        }

        return view('problem.problem_list', compact(
            'ccList', 'cc_selected', 'problem_list', 'arr_jml', 'datatable',
            'user'
        ));
    }

    public function searchResult(Request $request)
    {
        if($request->company_code==0)
            return redirect('report/problem')->with('warning','Company Code belum dipilih');

        $company_code = $request->company_code;

//        $ba_selected = '';
        $cc_selected = $company_code;
        $ccList[0] = 'Select Unit';

//        if(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat')) $baList = BusinessArea::all()->sortBy('id');
//        else $baList = BusinessArea::where('company_code', Auth::user()->company_code)->orderBy('id', 'asc')->get();

        $ccs = CompanyCode::all()->sortBy('id');

        foreach ($ccs as $wa) {
            $ccList[$wa->company_code] = $wa->company_code. ' - ' . $wa->description;
        }
        $company_code = CompanyCode::where('company_code', $cc_selected)->first();
        $problem_list = $company_code->problem;

        $jml_on_progress = $company_code->problem()->where('status',1)->get()->count();
        $jml_wait = $company_code->problem()->where('status',2)->get()->count();
        $jml_resolved = $company_code->problem()->where('status',3)->get()->count();
        $jml_closed = $company_code->problem()->where('status',4)->get()->count();

        $arr_jml = [$jml_on_progress, $jml_wait, $jml_resolved, $jml_closed];
//        $tahun = date('Y');

        return view('problem.problem_list', compact('ccList', 'cc_selected', 'problem_list', 'arr_jml'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if( Cache::has('list_grup_problem') ) {
            $list_grup = Cache::get('list_grup_problem');
        }
        else{
            $list_grup = ProblemGroup::all();
            Cache::forever( 'list_grup_problem', $list_grup);
        }
        $cc_selected = Auth::user()->company_code;

        if( Cache::has('coCodeList') ) {
            $coCodeList = Cache::get('coCodeList');
        }
        else{
            $coCodeList[0] = 'Select Company Code';
            foreach (CompanyCode::all()->sortBy('id') as $wa) {
                $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
            }
            Cache::forever( 'coCodeList', $coCodeList);
        }

//        $coCodeList[0] = 'Select Company Code';
//        foreach (CompanyCode::all()->sortBy('id') as $wa) {
//            $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
//        }

        $ba_selected = Auth::user()->business_area;
        $bsAreaList[0] = 'Select Unit';

        if(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat')) {
            foreach (BusinessArea::all()->sortBy('id') as $wa) {
                $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
            }
        }
        else {
            foreach (BusinessArea::where('company_code', $cc_selected)->get()->sortBy('id') as $wa) {
                $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
            }
        }
        return view('problem.problem_create', compact('list_grup', 'coCodeList', 'bsAreaList', 'cc_selected', 'ba_selected'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ProblemRequest $request)
    {
        $problem = new Problem();
        $problem->company_code = $request->company_code;
        $problem->business_area = $request->business_area;
        $problem->user_id_pelapor = Auth::user()->id;
        $problem->tgl_kejadian = Carbon::parse($request->tgl_kejadian);
        $problem->nip = $request->nip;
        $problem->nama = $request->nama;
        $problem->username = $request->username;
        $problem->email = $request->email;
        $problem->unit = $request->unit;
        $problem->server_id = $request->server_id;
        $problem->grup_id = $request->grup_id;
        $problem->status = '1';
        $problem->deskripsi = $request->deskripsi;
        $foto = $request->file('foto');
        if ($foto != null) {
            $problem->foto = date("YmdHis_").$foto->getClientOriginalName();

            Storage::put($request->business_area . '/problem/' . $problem->foto, File::get($foto));
        }

        $problem->save();

        Activity::log('Report problem ID:'.$problem->id, 'success');

        $role       = Role::find(1);
        $user_pusat = $role->users;
        foreach($user_pusat as $user) {
            $notif          = new Notification();
//            $notif->id      = $notif->getLastID();
            $notif->from    = Auth::user()->username2;
            $notif->to      = $user->username2;
            $notif->user_id_from = Auth::user()->id;
            $notif->user_id_to = $user->id;
            $notif->subject = 'Error Report';
            $notif->color = 'danger';
            $notif->icon = 'fa fa-exclamation-triangle';
            $notif->message = Auth::user()->name.' melaporkan error dengan ID: ' . str_pad($problem->id, 8, '0', STR_PAD_LEFT);
            $notif->url     = 'report/problem/' . $problem->id;
            $notif->save();

            $kepada = $user->name;
            $dari = Auth::user()->name;

            $mail = new MailLog();
            $mail->to = $user->email;
            $mail->to_name = $user->name;
            $mail->subject = '[KOMANDO] Error Reporting';
            $mail->file_view = 'emails.error_reporting';
            $mail->message = Auth::user()->name.' melaporkan error dengan ID: ' . str_pad($problem->id, 8, '0', STR_PAD_LEFT);
            $mail->status = 'CRTD';
            $mail->parameter = $problem->toJson();
            $mail->notification_id = $notif->id;
            $mail->jenis = '1';
            $mail->save();

//            if(env('ENABLE_EMAIL', true)) {
//                Mail::send('emails.release_ae1', ['kepada' => $kepada, 'dari' => $dari, 'ae1' => $ae1, 'notif' => $notif], function ($message) use ($user) {
//                    $message->to($user->email)
//                        ->subject('Permohonan Release Dokumen AE.1');
//                });
//            }
        }

        /*
        $notif          = new Notification();
//            $notif->id      = $notif->getLastID();
        $notif->from    = 'KOMANDO';
        $notif->to      = Auth::user()->username2;
//        $notif->user_id_from = Auth::user()->id;
        $notif->user_id_to = Auth::user()->id;
        $notif->subject = 'Error Report Sent';
        $notif->color = 'success';
//        $notif->icon = 'fa fa-exclamation-triangle';
        $notif->message = Auth::user()->name.' melaporkan error dengan ID: ' . str_pad($problem->id, 8, '0', STR_PAD_LEFT);
        $notif->url     = 'report/problem/' . $problem->id;
        $notif->save();

        $mail = new MailLog();
        $mail->to = Auth::user()->email;
        $mail->to_name = Auth::user()->name;
        $mail->subject = '[KOMANDO] Error Report Sent';
        $mail->file_view = 'emails.error_report_sent';
        $mail->message = Auth::user()->name.' melaporkan error dengan ID: ' . str_pad($problem->id, 8, '0', STR_PAD_LEFT);
        $mail->status = 'CRTD';
        $mail->parameter = $problem->toJson();
        $mail->notification_id = $notif->id;
        $mail->save();
        */
//        Mail::log('m.fahmi.rizal@pln.co.id','M Fahmi Rizal','TEST','test','TEST','CRTD');

        return redirect('report/problem')->with('success','Erorr report sent.');
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
        $problem = Problem::find($id);

        if( Cache::has('list_grup_problem') ) {
            $list_grup = Cache::get('list_grup_problem');
        }
        else{
            $list_grup = ProblemGroup::all();
            Cache::forever( 'list_grup_problem', $list_grup);
        }

        if( Cache::has('list_status') ) {
            $list_status = Cache::get('list_status');
        }
        else{
            $list_status = ProblemStatus::all();
            Cache::forever( 'list_status', $list_status);
        }

        $cc_selected = $problem->company_code;
        if( Cache::has('coCodeList') ) {
            $coCodeList = Cache::get('coCodeList');
        }
        else{
            $coCodeList[0] = 'Select Company Code';
            foreach (CompanyCode::all()->sortBy('id') as $wa) {
                $coCodeList[$wa->company_code] = $wa->company_code . ' - ' . $wa->description;
            }
            Cache::forever( 'coCodeList', $coCodeList);
        }

        $ba_selected = $problem->business_area;
        $bsAreaList[0] = 'Select Unit';

        if(Auth::user()->hasRole('root') || Auth::user()->hasRole('admin_pusat')) {
            foreach (BusinessArea::all()->sortBy('id') as $wa) {
                $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
            }
        }
        else {
            foreach (BusinessArea::where('company_code', $cc_selected)->get()->sortBy('id') as $wa) {
                $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
            }
        }
        return view('problem.problem_edit', compact('list_grup', 'coCodeList', 'bsAreaList', 'cc_selected', 'ba_selected', 'problem', 'list_status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, ProblemRequest $request)
    {
        $problem = Problem::find($id);
        $problem->company_code = $request->company_code;
        $problem->business_area = $request->business_area;
//        $problem->user_id_pelapor = Auth::user()->id;
        $problem->tgl_kejadian = Carbon::parse($request->tgl_kejadian);
        $problem->nip = $request->nip;
        $problem->nama = $request->nama;
        $problem->username = $request->username;
        $problem->email = $request->email;
        $problem->unit = $request->unit;
        $problem->server_id = $request->server_id;
        $problem->grup_id = $request->grup_id;
        $problem->deskripsi = $request->deskripsi;
        $foto = $request->file('foto');
        if ($foto != null) {
            $problem->foto = date("YmdHis_").$foto->getClientOriginalName();

            Storage::put($request->business_area . '/problem/' . $problem->foto, File::get($foto));
        }

        if(Auth::user()->hasRole('root')) {
            $problem->status = $request->status;
            $problem->cause = $request->cause;
            $problem->resolution = $request->resolution;
        }

        $problem->konfirmasi = $request->konfirmasi;
        $problem->save();

        Activity::log('Update report problem ID:'.$problem->id, 'success');

        if(!Auth::user()->hasRole('root')) {
            $problem->status = '1';
            $problem->save();

            $role = Role::find(1);
            $user_pusat = $role->users;
            foreach ($user_pusat as $user) {
                $notif = new Notification();
//            $notif->id      = $notif->getLastID();
                $notif->from = Auth::user()->username2;
                $notif->to = $user->username2;
                $notif->user_id_from = Auth::user()->id;
                $notif->user_id_to = $user->id;
                $notif->subject = 'Confirm Error Report';
                $notif->color = 'warning';
//            $notif->icon = 'fa fa-exclamation-triangle';
                $notif->message = Auth::user()->name . ' update error dengan ID: ' . str_pad($problem->id, 8, '0', STR_PAD_LEFT);
                $notif->url = 'report/problem/' . $problem->id;
                $notif->save();

//                $kepada = $user->name;
//                $dari = Auth::user()->name;

                $mail = new MailLog();
                $mail->to = $user->email;
                $mail->to_name = $user->name;
                $mail->subject = '[KOMANDO] Konfirmasi Error Reporting';
                $mail->file_view = 'emails.konfirmasi_error';
                $mail->message = Auth::user()->name.' konfirmasi error dengan ID: ' . str_pad($problem->id, 8, '0', STR_PAD_LEFT);
                $mail->status = 'CRTD';
                $mail->parameter = $problem->toJson();
                $mail->notification_id = $notif->id;
                $mail->jenis = '1';
                $mail->save();

//            if(env('ENABLE_EMAIL', true)) {
//                Mail::send('emails.release_ae1', ['kepada' => $kepada, 'dari' => $dari, 'ae1' => $ae1, 'notif' => $notif], function ($message) use ($user) {
//                    $message->to($user->email)
//                        ->subject('Permohonan Release Dokumen AE.1');
//                });
//            }
            }
        }
        else{
            $pelapor = User::find($problem->user_id_pelapor);
            if($pelapor==null){
                $role_admin_1 = Role::find(6);
                $pelapor = $role_admin_1->users()->where('company_code',$problem->company_code)->first();
                $problem->user_id_pelapor = $pelapor->id;
                $problem->save();
            }
            $notif = new Notification();
//            $notif->id      = $notif->getLastID();
            $notif->from = Auth::user()->username2;
            $notif->to = $pelapor->username2;
            $notif->user_id_from = Auth::user()->id;
            $notif->user_id_to = $pelapor->id;
            $notif->subject = 'Confirm Error Report';
//            $notif->color = 'warning';
//            $notif->icon = 'fa fa-exclamation-triangle';
            $notif->message = $problem->statusProblem->status . ' for error ID: ' . str_pad($problem->id, 8, '0', STR_PAD_LEFT).'. Silakan melakukan konfirmasi.';
            $notif->url = 'report/problem/' . $problem->id;
            $notif->save();

//            $kepada = $pelapor->name;
//            $dari = Auth::user()->name;

            $mail = new MailLog();
            $mail->to = $pelapor->email;
            $mail->to_name = $pelapor->name;
            $mail->subject = '[KOMANDO] Konfirmasi Error Reporting';
            $mail->file_view = 'emails.konfirmasi_error_user';
            $mail->message = Auth::user()->name.' konfirmasi error dengan ID: ' . str_pad($problem->id, 8, '0', STR_PAD_LEFT);
            $mail->status = 'CRTD';
            $mail->parameter = $problem->toJson();
            $mail->notification_id = $notif->id;
            $mail->jenis = '1';
            $mail->save();

//            if(env('ENABLE_EMAIL', true)) {
//                Mail::send('emails.release_ae1', ['kepada' => $kepada, 'dari' => $dari, 'ae1' => $ae1, 'notif' => $notif], function ($message) use ($user) {
//                    $message->to($user->email)
//                        ->subject('Permohonan Release Dokumen AE.1');
//                });
//            }
        }


        return redirect('report/problem')->with('success','Erorr report updated.');
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

    public function getFoto($id){
        $problem = Problem::find($id);

        try {
//            $file = Storage::get('foto_pegawai/' . strtoupper($user->strukturJabatan->nip) . '_.jpg');
            $file = Storage::get($problem->business_area . '/problem/' . $problem->foto);
        }
        catch(\Exception $e){
            $file = file_get_contents(asset('assets/images/blank.png'));
        }

//        $img = Image::make($file)->resize(64, 64);
//        $img = Image::make($file);

//        return $file;

        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg');

    }

    public function close($id)
    {
        $problem = Problem::find($id);
        $problem->status = '4';

        $problem->save();

        Activity::log('Close report problem ID:'.$problem->id, 'success');

        /*
        $role       = Role::find(1);
        $user_pusat = $role->users;
        foreach($user_pusat as $user) {
            $notif          = new Notification();
//            $notif->id      = $notif->getLastID();
            $notif->from    = Auth::user()->username2;
            $notif->to      = $user->username2;
            $notif->user_id_from = Auth::user()->id;
            $notif->user_id_to = $user->id;
            $notif->subject = 'Close Error Report';
            $notif->color = 'success';
            $notif->icon = 'fa fa-check';
            $notif->message = 'Error ID: ' . str_pad($problem->id, 8, '0', STR_PAD_LEFT).' solved';
            $notif->url     = 'report/problem/' . $problem->id;
            $notif->save();

//            $kepada = $user->name;
//            $dari = Auth::user()->name;

            $mail = new MailLog();
            $mail->to = $user->email;
            $mail->to_name = $user->name;
            $mail->subject = '[KOMANDO] Error Reporting Closed';
            $mail->file_view = 'emails.error_closed';
            $mail->message = 'Error ID: ' . str_pad($problem->id, 8, '0', STR_PAD_LEFT).' closed.';
            $mail->status = 'CRTD';
            $mail->parameter = $problem->toJson();
            $mail->notification_id = $notif->id;
            $mail->save();

//            if(env('ENABLE_EMAIL', true)) {
//                Mail::send('emails.release_ae1', ['kepada' => $kepada, 'dari' => $dari, 'ae1' => $ae1, 'notif' => $notif], function ($message) use ($user) {
//                    $message->to($user->email)
//                        ->subject('Permohonan Release Dokumen AE.1');
//                });
//            }
        }

        */
        return redirect('report/problem')->with('success','Erorr report closed.');
    }
}
