<?php

namespace App\Http\Controllers;

use App\AttachmentMateri;
use App\JenisMateri;
use App\Materi;
use App\Services\Datatable;
use App\Tema;
use App\Activity;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Request;

class MateriController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    ini_set('max_execution_time', 36000);

    $tahun = request('tahun', date('Y'));
    $jenis_materi_id = request('jenis_materi_id', 1);

    if ((Auth::user()->company_code == '1000' && Auth::user()->hasRole('admin_pusat')) || Auth::user()
            ->hasRole('root')) {
      $query = Materi::with('tema', 'jenisMateri', 'companyCode', 'penulis', 'businessArea', 'strukturOrganisasi')
          ->whereYear('tanggal', '=', $tahun)
          ->where('jenis_materi_id', $jenis_materi_id)
          ->orderBy('id','desc');
          
    } else {
      $query = Materi::with('tema', 'jenisMateri', 'companyCode', 'penulis', 'businessArea', 'strukturOrganisasi')
          ->where('company_code', Auth::user()->company_code)
          ->whereYear('tanggal', '=', $tahun)
          ->where('jenis_materi_id', $jenis_materi_id)
          ->orderBy('id','desc');
    }

    $datatable = Datatable::make($query)
        ->rowView('master.materi_list_row')
        ->columns([
            ['data' => 'judul', 'searchable' => false, 'orderable' => true],
            ['data' => 'penulis', 'searchable' => false, 'orderable' => false],
            ['data' => 'tema', 'searchable' => false, 'orderable' => false],
            ['data' => 'jenis', 'searchable' => false, 'orderable' => false],
            ['data' => 'cluster', 'searchable' => false, 'orderable' => false],
            ['data' => 'company_code', 'searchable' => false, 'orderable' => false],
            ['data' => 'business_area', 'searchable' => false, 'orderable' => false],
            ['data' => 'struktur_organisasi', 'searchable' => false, 'orderable' => false],
            ['data' => 'jumlah_like', 'searchable' => false, 'orderable' => false],
            ['data' => 'jumlah_dislike', 'searchable' => false, 'orderable' => false],
            ['data' => 'rating_1', 'searchable' => false, 'orderable' => false],
            ['data' => 'rating_2', 'searchable' => false, 'orderable' => false],
            ['data' => 'rating_3', 'searchable' => false, 'orderable' => false],
            ['data' => 'rating_4', 'searchable' => false, 'orderable' => false],
            ['data' => 'rating_5', 'searchable' => false, 'orderable' => false],
            ['data' => 'total_review', 'searchable' => false, 'orderable' => false],
            ['data' => 'rata_rata', 'searchable' => false, 'orderable' => false],
        ]);

    if (request()->wantsJson()) {
      $datatable->search(function ($query, $keyword) {
        $keyword = strtolower($keyword);
        $query->where(function ($q) use ($keyword) {
          $q->where(DB::raw('lower(judul)'), 'like', "%$keyword%")

          ->orWhereHas('tema', function ($q2) use ($keyword) {
            $q2->where(DB::raw('lower(tema)'), 'like', "%$keyword%");
          })

          ->orWhereHas('penulis', function ($q2) use ($keyword) {
            $q2->where(DB::raw('lower(cname)'), 'like', "%$keyword%");
          })

          ->orWhereHas('businessArea', function ($q2) use ($keyword) {
            $q2->where(DB::raw('lower(description)'), 'like', "%$keyword%");
          })

          ->orWhereHas('companyCode', function ($q2) use ($keyword) {
            $q2->where(DB::raw('lower(description)'), 'like', "%$keyword%");
          })

          ->orWhereHas('strukturOrganisasi', function ($q2) use ($keyword) {
            $q2->where(DB::raw('lower(stext)'), 'like', "%$keyword%");
          })

          ->orWhereHas('jenisMateri', function ($q2) use ($keyword) {
            $q2->where(DB::raw('lower(jenis)'), 'like', "%$keyword%");
          });
        });
      });

      return $datatable->toJson();
    }

    return view('master.materi_list', compact('materi_list', 'datatable', 'tahun', 'jenis_materi_id'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $tema_list[] = 'Select Tema';
    foreach (Tema::all()->sortBy('id') as $wa) {
      $tema_list[$wa->id] = $wa->tema;
    }

    $jenis_list[] = 'Select Jenis';
    foreach (JenisMateri::all()->sortBy('id') as $wa) {
      $jenis_list[$wa->id] = $wa->jenis;
    }

//    dd($tema_list);
    return view('master.materi_create', compact('tema_list', 'jenis_list'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $file = $request->file('materi');
//        if ($file == null) {
//            return redirect('coc/create/')->with('warning', 'File materi belum dipilih');
//        }
    if ($file != null) {
      $extension = strtolower($file->getClientOriginalExtension());
      if ($extension != 'pdf') {
        return redirect('master-data/materi')->with('warning', 'File yang diupload bukan berekstensi PDF.');
      }
    }

    $materi = Materi::create($request->all());

    $materi->pernr_penulis = $request->pernr_penulis;
    $materi->company_code = Auth::user()->company_code;
    $materi->business_area = Auth::user()->business_area;
    $materi->orgeh = Auth::user()->getOrgLevel()->objid;

    $materi->save();

    if($file != null) {
      $attachment = new AttachmentMateri();
      $attachment->materi_id = $materi->id;
      $attachment->judul = $materi->judul;
      $attachment->filename = $file->getClientOriginalName();
      $attachment->save();

      Storage::put($materi->business_area . '/attachment_materi/' . $file->getClientOriginalName(), File::get($file));
    }


    return redirect('master-data/materi')->with('success','Materi berhasil disimpan');
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
    $tema_list[] = 'Select Tema';
    foreach (Tema::all()->sortBy('id') as $wa) {
      $tema_list[$wa->id] = $wa->tema;
    }

    $jenis_list[] = 'Select Jenis';
    foreach (JenisMateri::all()->sortBy('id') as $wa) {
      $jenis_list[$wa->id] = $wa->jenis;
    }

    $materi = Materi::findOrFail($id);
    $attachment = $materi->attachments()->first();

//    dd($tema_list);
    return view('master.materi_edit', compact('tema_list', 'jenis_list', 'materi', 'attachment'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, $id)
  {

//    if($request->pernr_penulis=='' && $request->energize_day==''){
//        return redirect()->back()->withErrors('Penulis materi wajib diisi')->withInput();
//    }

    if($request->judul==''){
      return redirect()->back()->withErrors('Judul materi wajib diisi')->withInput();
    }

    if($request->deskripsi==''){
      return redirect()->back()->withErrors('Deskripsi materi wajib diisi')->withInput();
    }

    // dd($request);

    // $materi_existing = Materi::whereDate('tanggal', '=', Date::parse($request->tanggal_coc)->format('Y-m-d'))->where('company_code', Auth::user()->company_code)->first();

    // if($materi_existing!=null) {
    //   Date::setLocale('id');
    //   return redirect('coc')->with('warning', 'Sudah ada materi pada tanggal ' . Date::parse($request->tanggal_coc)->format('d F Y'));
    // }

    $file = $request->file('materi');
    if ($file != null) {
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension != 'pdf') {
          return redirect()->back()->withErrors('File yang diupload bukan berekstensi PDF.')->withInput();
            // return redirect('coc/create/' . $scope)->with('warning', 'File yang diupload bukan berekstensi PDF.');
        }
    }
    // $tanggal_coc = Carbon::parse($request->tanggal_coc);

    // cek tema pada tanggal tsb
    // $thematic = TemaCoc::where('start_date', '<=', $tanggal_coc)
    //     ->where('end_date', '>=', $tanggal_coc)
    //     ->first();

    // jika belum ada tema
    // if ($thematic == null)
    //     return redirect('coc')->with('warning', 'Tema belum tersedia. Mohon tunggu sampai ada tema dari Kantor Pusat.');

    // if ($scope == 'nasional') {
    //     $lokasi = 'Kantor Pusat';
    //     $all_day = '1';
    //     $bg = 'bg-pink';
    //     $jenis_materi_id = '1';
    // } elseif ($scope == 'unit') {
    //     $lokasi = Auth::user()->companyCode->description;
    //     $all_day = '1';
    //     $bg = 'bg-purple';
    //     $jenis_materi_id = '2';
    // } elseif ($scope == 'local') {
    //     $lokasi = $request->lokasi;
    //     $all_day = '0';
    //     $bg = 'bg-primary';
    //     $jenis_materi_id = '3';
    // }

    // jika sudah ada tema
    // $event = new Event();
    // $event->title = $request->judul;
    // $event->all_day = $all_day;
    // $event->start = $tanggal_coc;
    // $event->class_name = $bg;

    // $event->save();

    // create materi
    $materi = Materi::find($id);
    // $materi->event_id = $event->id;
    // $materi->tema_id = $thematic->tema_id;
      if($request->pernr_penulis!='')
        $materi->pernr_penulis = $request->pernr_penulis;
    $materi->judul = $request->judul;
    $materi->deskripsi = $request->deskripsi;
    // $materi->jenis_materi_id = $jenis_materi_id;

    // $materi->company_code = Auth::user()->company_code;
    // $materi->business_area = Auth::user()->business_area;
    // $materi->orgeh = Auth::user()->getOrgLevel()->objid;

    // $materi->tanggal = $tanggal_coc;

    $materi->energize_day = $request->energize_day;

        //  dd($materi);

    $materi->save();

    if ($file != null) {
      if($materi->attachments()->first()==null)
        $attachment = new AttachmentMateri();
      else
        $attachment = $materi->attachments()->first();

        $attachment->materi_id = $materi->id;
        $attachment->judul = $materi->judul;
        $attachment->filename = $file->getClientOriginalName();
        $attachment->save();

        Storage::put($materi->business_area . '/attachment_materi/' . $file->getClientOriginalName(), File::get($file));
    }

    // if($jenis_materi_id=='1') $txt_jenis = 'Nasional';
    // elseif($jenis_materi_id=='2') $txt_jenis = 'GM';
    // else $txt_jenis = '';
    Activity::log('Edit Materi : '.$materi->judul.' (ID: '.$materi->id, 'success');

    // notifikasi ke para admin CoC

    // $judul          = $materi->judul;
    // $tanggal        = $materi->tanggal->format('d-m-Y');
    // $id_materi      = $materi->id;
    // $tema           = $materi->tema->tema;
    // $jenis_materi   = $materi->jenisMateri->jenis;
    // $penulis        = @$materi->penulis->cname;
    // $jabatan_penulis = @$materi->penulis->strukturPosisi->stext;
    // $energize_day   = $materi->energize_day;

    // $role_admin = Role::find(4);
    // if($materi->jenis_materi_id=='1') {
    //     $admin_list = $role_admin->users;
    //     $color = 'pink';
    // }
    // else {
    //     $admin_list = $role_admin->users()->where('company_code', $materi->company_code)->get();
    //     $color = 'purple';
    // }
    // foreach($admin_list as $user){

    //     $notif          = new Notification();
    //     $notif->from    = Auth::user()->username2;
    //     $notif->to      = $user->username2;
    //     $notif->user_id_from = Auth::user()->id;
    //     $notif->user_id_to = $user->id;
    //     $notif->subject = 'Materi '.$jenis_materi.' Telah Terbit';
    //     $notif->color = $color;
    //     $notif->icon = 'fa fa-book';

    //     $notif->message = $judul.'. Silakan membuat jadwal CoC untuk materi tersebut.';
    //     $notif->url     = 'coc/initial/' . $id_materi;

    //     $notif->save();

    //     $mail               = new MailLog();
    //     $mail->to           = $user->email;
    //     $mail->to_name      = $user->name;
    //     $mail->subject      = '[KOMANDO] Materi '.$jenis_materi.' Telah Diterbitkan';
    //     $mail->file_view    = 'emails.materi_created';
    //     $mail->message      = 'Materi '.$jenis_materi.' dengan judul '.$judul.' sudah dibuat untuk tanggal '.$tanggal.'. Silakan membuat jadwal CoC untuk materi tersebut.';
    //     $mail->status       = 'CRTD';
    //     $mail->parameter    = '{"tema":"'.$tema.'","jenis":"'.$jenis_materi.'","judul":"'.$judul.'","penulis":"'.$penulis.'","jabatan_penulis":"'.$jabatan_penulis.'","tanggal":"'.$tanggal.'","energize_day":"'.$energize_day.'"}';
    //     $mail->notification_id = $notif->id;
    //     $mail->jenis = '2';

    //     $mail->save();
    // }

    return redirect('master-data/materi')->with('success', 'Materi berhasil diubah.');
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

  public function exportMateri($tahun, $jenis_materi_id)
  {
    ini_set('max_execution_time', 300);
    $jenis_materi_id = $jenis_materi_id;

    $materi_list = Materi::whereYear('tanggal', '=', $tahun)
                    ->where('jenis_materi_id', $jenis_materi_id)
                    ->orderBy('id','desc')->get();

    Excel::create(date('YmdHis') . '_materi_coc_' . $tahun, function ($excel) use ($materi_list, $tahun) {

        $excel->sheet('Materi CoC', function ($sheet) use ($materi_list, $tahun) {
            $sheet->loadView('master/template_materi')
                ->with('materi_list', $materi_list)
                ->with('tahun', $tahun);
        });
    })->download('xlsx');
  }

}

?>
