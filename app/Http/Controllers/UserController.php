<?php

namespace App\Http\Controllers;

use Adldap\Adldap;
use Adldap\Auth\BindException;
use App\Activity;
use App\BusinessArea;
use App\Email;
use App\Http\Requests\UserRequest;
use App\PA0001;
use App\PegawaiAktif;
use App\Role;
use App\RoleProduction;
use App\Services\Datatable;
use App\StrukturJabatan;
use App\StrukturOrganisasi;
use App\StrukturPosisi;
use App\SuhuBadan;
use App\User;
use App\UserProduction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\UnitKerjaService;
use App\UnitKerja;
use App\Utils\BasicUtil;
use App\Utils\BusinessAreaUtil;
use App\Utils\UnitKerjaUtil;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;
use Mockery\CountValidator\Exception;
use Psy\Exception\ErrorException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('root') || $user->hasRole('admin_ki') || $user->hasRole('admin_liquid_ki')) {
            $business_area = $request->business_area;
        } elseif ($user->hasRole('admin_htd')) {
            $businessArea = (new UnitKerjaUtil)->shiftingBusinessArea($user);
            $array = $user->business_area;
            foreach($businessArea as $key => $value) {
                if($value != "1001") {
                    $array = $value;
                    break;
                }
            }
            $business_area = $array;
        } else {
            $business_area = $user->business_area;
        }

        $role = (int) $request->role;
        $search = $request->search;

        $unitKerja = UnitKerja::where('business_area', $business_area)->pluck('user_id')->toArray();

        if (! empty($request->oba) && $request->oba !== $business_area) {
            $role = 0;
        }

        $query = User::with('unitKerjas.businessArea.companyCode')
            ->whereHas('roles', function ($query) use ($role) {
                return $query->when(! empty($role), function ($query) use ($role) {
                    return $query->where('id', $role);
                });
            })
            ->where('status', 'ACTV')
            ->where(function ($query) use ($unitKerja, $business_area, $search) {
                return $query->when(count($unitKerja) > 0, function ($query) use ($business_area) {
                    return $query->where('business_area', $business_area);
                })
                ->when(empty($unitKerja), function ($query) use ($business_area) {
                    return $query->where('business_area', $business_area);
                })
                ->when(! empty($search), function ($query) use ($search) {
                    $keyword = strtolower($search['value']);

                    return $query->where(function ($query) use ($keyword) {
                        return $query->where('lower(name)', 'like', "%$keyword%")
                            ->orWhere('nip', 'like', "%$keyword%")
                            ->orWhere('lower(username)', 'like', "%$keyword%")
                            ->orWhere('lower(email)', 'like', "%$keyword%")
                            ->orWhere('lower(ad_company)', 'like', "%$keyword%")
                            ->orWhere('company_code', 'like', "%$keyword%")
                            ->orWhere('business_area', 'like', "%$keyword%");
                    });
                });
            });

        $datatable = Datatable::make($query)
            ->rowView('components.datatable-columns.user')
            ->columns([
                ['data' => 'avatar', 'searchable' => false, 'orderable' => false],
                ['data' => 'username', 'searchable' => false],
                ['data' => 'nip', 'searchable' => false],
                ['data' => 'name', 'searchable' => false],
                ['data' => 'email', 'searchable' => false],
                ['data' => 'ad_company', 'searchable' => false],
                ['data' => 'company_code', 'searchable' => false],
                ['data' => 'business_area', 'searchable' => false],
                ['data' => 'roles', 'searchable' => false, 'orderable' => false],
                ['data' => 'action', 'searchable' => false, 'orderable' => false],
            ]);

        if (request()->wantsJson()) {
            return $datatable->toJson();
        }

        $bs_selected = BusinessArea::where('business_area', $business_area)->first();
        $bsList[0] = 'Select Business Area';

        if ($user->hasRole('root')) {
            foreach (BusinessArea::all()->sortBy('id') as $wa) {
                $bsList[$wa->business_area] = $wa->business_area.' - '.$wa->description;
            }
        } elseif($user->hasRole('admin_htd')) {
            $bs_selected = BusinessArea::where('business_area', $business_area)->first();
            // dd($bs_selected);
            $companyCode = (new UnitKerjaUtil)->shiftingCompanyCode($user);
            $array = array();
            foreach($companyCode as $key => $value) {
                if($value != "1000") {
                    $array[] = $value;
                }
            }
            foreach (BusinessArea::whereIn('company_code', $array)->get() as $wa) {
                $bsList[$wa->business_area] = $wa->business_area.' - '.$wa->description;
            }   
        }
        else {
            if ($business_area == null) {
                $bs_selected = $user->businessArea;
            }
            foreach (BusinessArea::where('company_code', $bs_selected->company_code)->get() as $wa) {
                $bsList[$wa->business_area] = $wa->business_area.' - '.$wa->description;
            }
        }

        $roles = ['Semua Role'] + (new BasicUtil)->getRolesArray();

        return view('user.user_list', compact('bsList', 'bs_selected', 'datatable', 'roles', 'role', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $user   = User::findOrFail($id);
//        $roles = Role::all()->sortBy('id');
//        foreach (BusinessArea::all()->sortBy('id') as $wa) {
//            $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
//        }
//
//        return view('user.user_create', compact('roles', 'bsAreaList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->password != $request->password_again) {
//            return redirect('user-management/user/create')->withInput($request->input());
            return back()->withInput()->with('error', 'Password did not match.');
        }
        $user = new User;

        $user->domain = $request->domain;
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->active_directory = 0;
//        $user->business_area    = $request->business_area;
//        $business_area          = BusinessArea::where('business_area',$request->business_area)->first();
//        $user->company_code     = $business_area->companyCode->company_code;
        $user->save();

        $roles = $request->roles;
        for ($x = 0; $x < count($roles); $x++) {
            $user->roles()->attach($roles[$x]);
        }

        Activity::log('Add new user <a href="' . url('/user-management/user/' . $user->id) . '">' . $user->name . '</a>.', 'success');

        return redirect('user-management/user')->with('success', 'User berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        dd($id);
//        $modules = Module::all()->sortBy('id');

        $user = User::findOrFail($id);
        $roles = Role::all()->sortBy('id');
//        foreach (BusinessArea::all()->sortBy('id') as $wa) {
//            $bsAreaList[$wa->business_area] = $wa->business_area . ' - ' . $wa->description;
//        }

        return view('user.user_display', compact('user', 'roles'));
    }

    public function showFromNIP($nip)
    {
        $user = User::where('nip',$nip)->first();
        if($user!=null){
            $roles = Role::all()->sortBy('id');

            return view('user.user_display', compact('user', 'roles'));
        }
        return redirect()->back()->with('error','NIP tidak ditemukan');
    }

    public function showFromUsername($username)
    {
        $user = User::where('username',$username)->first();
        if($user!=null){
            $roles = Role::all()->sortBy('id');

            return view('user.user_display', compact('user', 'roles'));
        }
        return redirect()->back()->with('error','Username tidak ditemukan');
    }

    public function editStruktur($id)
    {
        $userAuth = auth()->user();
        $user = User::with('unitKerjas')->findOrFail($id);
        $roles = Role::whereIn('id', [4, 5, 6, 10, 11, 14, 15])->orderBy('id', 'asc')->get();
        if($userAuth->hasRole('admin_htd') || $userAuth->hasRole('admin_liquid_ki') || $userAuth->hasRole('admin_liquid_unit')) {
            $roles = Role::whereIn('id', [4, 5, 6, 10, 11, 15])->orderBy('id', 'asc')->get();
        }
        $businessAreaOpts = (new BusinessAreaUtil())->getAll()->toSelectOptions();
        $businessAreaSelected = (new UnitKerjaUtil())->shiftingBusinessArea($user);

        return view('user.user_edit_struktur', compact('user', 'businessAreaOpts', 'businessAreaSelected', 'roles'));
    }

    public function updateStruktur(UserRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::find($request->user_id);
            // $posisi = $user->strukturPosisi();
            // $struktur = $user->strukturJabatan;

            $plans = $user->plans;

            $bidang = $request->bidang;

            // jika posisi atau organisasi berubah
            if ($request->orgeh != @$user->orgeh || $request->posisi != @$user->jabatan) {
                $org_baru = StrukturOrganisasi::where('objid', $request->orgeh)->first();
                $bidang = $org_baru->stext;

                // create posisi baru
                $last_org = StrukturPosisi::orderBy('id', 'desc')->first();
                $last_number = $last_org->id + 1;
                $posisi_baru = new StrukturPosisi();
                $posisi_baru->id = $last_number;
                $posisi_baru->objid = $last_number;
                $posisi_baru->stext = strtoupper($request->posisi);
                $posisi_baru->relat = '99';
                $posisi_baru->sobid = $request->orgeh;
                $posisi_baru->stxt2 = strtoupper($org_baru->stext);
                $posisi_baru->save();

                $plans = $posisi_baru->objid;

                // $struktur->orgeh = $request->orgeh;
                // $struktur->plans = $posisi_baru->objid;
                // $struktur->save();
            }

            // Update Role
            $user->roles()->sync([]);
            $roles = $request->roles;

            for ($x = 0; $x < count($roles); $x++) {
                $user->roles()->attach($roles[$x]);
            }

            // Update user
            $user->ad_company = $request->company;
            $user->ad_department = $request->ad_department;
            $user->username = $request->username;
            $user->username2 = 'pusat\\' . $request->username;
            $user->email = $request->email;
            $user->nip = $request->nip;
            
            $user->orgeh = $request->orgeh;
            $user->plans = $plans;
            $user->jabatan = $request->posisi;
            $user->bidang = $bidang;

            $user->save();

            // using unit_kerja
            $unitKerjaService = new UnitKerjaService();
            $unitKerjaService->store(BusinessArea::whereIn('business_area', $request->business_area)->get(), $user);

            Activity::log('Update data user ' . $user->name . ' (' . $user->nip . ') ', 'success');

            DB::commit();

            return redirect('user-management/user')->with('success', 'Data user ' . $user->name . ' (' . $user->nip . ') berhasil diubah');
        } catch (\Exception $th) {
            DB::rollback();

            return redirect('user-management/user')->with('error', 'Data user ' . $user->name . ' (' . $user->nip . ') gagal diubah. Karen ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all()->sortBy('id');
//        foreach (BusinessArea::all()->sortBy('id') as $wa) {
//            $bsAreaList[$wa->id] = $wa->business_area . ' - ' . $wa->description;
//        }

        return view('pengguna.user_edit', compact('user', 'roles'));
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
//        $user = User::findOrFail($id);
//        if ($user->domain == 'esdm') {
//            $user->username         = $request->username;
//            $user->name             = $request->name;
//            $user->email            = $request->email;
//        }
//        else{
//            $user->business_area    = $request->business_area;
//            $business_area          = BusinessArea::where('business_area', $request->business_area)->first();
//            $user->company_code     = $business_area->companyCode->company_code;
//        }
//        $user->save();
//
//        // delete relationship data
//        $user->roles()->sync([]);
//
//        $roles = $request->roles;
//        for ($x = 0; $x < count($roles); $x++) {
//            $user->roles()->attach($roles[$x]);
//        }
//
//        Activity::log('Update user <a href="' . url('/user-management/user/' . $user->id) . '">' . $user->name . '</a>.', 'success');
//
//        return redirect('user-management/user')->with('success', 'User berhasil diubah');
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->roles()->sync([]);

        $roles = $request->roles;
//        dd($roles);
        for ($x = 0; $x < count($roles); $x++) {
            $user->roles()->attach($roles[$x]);
        }

        return redirect('user-management/user/' . $user->id)->with('success', 'Role berhasil disimpan');
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

    public function importEmail()
    {
        $jabatan = StrukturJabatan::all();

        $x = 1;
        $y = 1;
        foreach ($jabatan as $peg) {

            $email = Email::where('pernr', $peg->pernr)->first();
            if ($email != null) {
                $peg->email = strtolower($email->email);
                $peg->save();
                $y++;
            }
//            echo $peg->pernr." : ".$peg->email."<br>";
            $x++;
        }
        echo $y . "/" . $x . ' data imported.';
    }

    public function _getFoto($id)
    {
//        $entry = UploadData::where('filename', '=', $filename)->firstOrFail();
        $user = User::findOrFail($id);
        $file = Storage::get($user->business_area . '/foto/' . $user->foto);
//        try {
//            $file = Storage::get('foto_pegawai/' . strtoupper($user->strukturJabatan->nip) . '_.jpg');
//        }
//        catch(\Exception $e){
//            $file = file_get_contents(asset('assets/images/user.jpg'));
//        }
//        dd($file);

        $img = Image::make($file);

        return $img->response('jpg');

//        return (new Response($file, 200))
//            ->header('Content-Type', 'image/jpeg')
//            ->header('Content-Disposition', 'attachment; filename="' . $user->strukturJabatan->nip . '.jpg"');

    }

    public function getFotoUserThumbnail()
    {

        $user = Auth::user();
        try {
//            $file = Storage::get('foto_pegawai/' . strtoupper($user->strukturJabatan->nip) . '_.jpg');
            $file = Storage::get($user->business_area . '/foto/' . $user->foto);
        } catch (\Exception $e) {
            $file = file_get_contents(asset('assets/images/user.jpg'));
        }

        $img = Image::make($file)->resize(64, 64);

        return $img->response('jpg');

    }

    public function _getFotoThumbnail($id)
    {
//        $entry = UploadData::where('filename', '=', $filename)->firstOrFail();
        $user = User::findOrFail($id);
//        $file = Storage::get($user->business_area . '/foto/' . $user->foto);
        try {
//            $file = Storage::get('foto_pegawai/' . strtoupper($user->strukturJabatan->nip) . '_.jpg');
            $file = Storage::get($user->business_area . '/foto/' . $user->foto);
        } catch (\Exception $e) {
            $file = file_get_contents(asset('assets/images/user.jpg'));
        }
//        dd($file);

        $img = Image::make($file)->resize(128, 128);

        return $img->response('jpg');

//        return (new Response($file, 200))
//            ->header('Content-Type', 'image/jpeg')
//            ->header('Content-Disposition', 'attachment; filename="' . $user->strukturJabatan->nip . '.jpg"');

    }

    public function getFotoFromNIP($nip)
    {
//        $entry = UploadData::where('filename', '=', $filename)->firstOrFail();
//        $user = User::where('',$id);
//        $file = Storage::get($user->business_area . '/foto/' . $user->foto);
        try {
            $file = Storage::get('foto_pegawai/' . strtoupper($nip) . '.jpg');
        } catch (\Exception $e) {
            $file = file_get_contents(asset('assets/images/user.jpg'));
        }
//        dd($file);

//        $img = Image::make($file)->crop(64, 64, 0, 0);
        $img = Image::make($file);

        return $img->response('jpg');

//        return (new Response($file, 200))
//            ->header('Content-Type', 'image/jpeg')
//            ->header('Content-Disposition', 'attachment; filename="' . strtoupper($nip) . '.jpg"');

    }

    public function getFotoFromNIPThumbnail($nip)
    {
//        $entry = UploadData::where('filename', '=', $filename)->firstOrFail();
//        $user = User::where('',$id);
//        $file = Storage::get($user->business_area . '/foto/' . $user->foto);
        //try {
        //    $file = Storage::get('foto_pegawai/' . strtoupper($nip) . '.jpg');
        //    $img = Image::make($file)->resize(64, 64);
        //} catch (\Exception $e) {
        $file = file_get_contents(asset('assets/images/user.jpg'));
        $img = Image::make($file);
        //}
//        dd($file);

        return $img->response('jpg');

//        return (new Response($file, 200))
//            ->header('Content-Type', 'image/jpeg')
//            ->header('Content-Disposition', 'attachment; filename="' . strtoupper($nip) . '.jpg"');

    }

    public function getFoto($id)
    {
        $user = User::findOrFail($id);
        if ($user->foto != null) {
            if (Storage::exists('foto/' . $user->foto)) {
                $file = Storage::get('foto/' . $user->foto);
            } else {
                $file = Storage::get('foto/default.jpg');
            }
        } else {
            $file = Storage::get('foto/default.jpg');
        }
//        dd($file);

        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg');
//            ->header('Content-Disposition', 'attachment; filename="' . Auth::user()->foto . '"');

    }

    public function getFotoPegawai($nip)
    {
        $user = User::where('nip', $nip)->first();
        if ($user->foto != null) {
            if (Storage::exists('foto/' . $user->foto)) {
                $file = Storage::get('foto/' . $user->foto);
            } else {
                $file = Storage::get('foto/default.jpg');
            }
        } else {
            $file = Storage::get('foto/default.jpg');
        }

        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg');

    }

    public function getFotoUser()
    {
        if (Auth::user()->foto != null) {
            if (Storage::exists('foto/' . Auth::user()->foto)) {
                $file = Storage::get('foto/' . Auth::user()->foto);
            } else {
                $file = Storage::get('foto/default.jpg');
            }
        } else {
            $file = Storage::get('foto/default.jpg');
        }
//        dd($file);

        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg');
//            ->header('Content-Disposition', 'attachment; filename="' . Auth::user()->foto . '"');

    }

    public function getFotoThumb()
    {
        $path = 'foto-thumb/default.jpg';

        if (Auth::user()->foto != null) {
            if (Storage::exists('foto-thumb/' . Auth::user()->foto)) {
                $path = 'foto-thumb/' . Auth::user()->foto;
            }
        }

        try {
            $file = Storage::get($path);

            return (new Response($file, 200))->header('Content-Type', 'image/jpeg');

        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $exception) {
            return Image::make(public_path('assets/images/users/avatar-1.jpg'))->response();
        }
    }

    public function getFotoThumbnail($id)
    {
        $user = User::findOrFail($id);
        if ($user->foto != null) {
            if (Storage::exists('foto-thumb/' . $user->foto)) {
                $file = Storage::get('foto-thumb/' . $user->foto);
            } else {
                $file = Storage::get('foto-thumb/default.jpg');
            }
        } else {
            $file = Storage::get('foto-thumb/default.jpg');
        }
//        dd($file);

        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg');
//            ->header('Content-Disposition', 'attachment; filename="' . Auth::user()->foto . '"');

    }

    public function getFotoThumbnailPegawai($nip)
    {
        $user = User::where('nip', $nip)->first();
        if ($user->foto != null) {
            if (Storage::exists('foto-thumb/' . $user->foto)) {
                $file = Storage::get('foto-thumb/' . $user->foto);
            } else {
                $file = Storage::get('foto-thumb/default.jpg');
            }
        } else {
            $file = Storage::get('foto-thumb/default.jpg');
        }

        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg');

    }

    public function getFotoIcon($filename)
    {
        if ($filename != null) {
            if (Storage::exists('foto-thumb/' . $filename)) {
                $file = Storage::get('foto-thumb/' . $filename);
            } else {
                $file = Storage::get('foto-thumb/default.jpg');
            }
        } else {
            $file = Storage::get('foto-thumb/default.jpg');
        }
//        dd($file);

        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg');
//            ->header('Content-Disposition', 'attachment; filename="' . Auth::user()->foto . '"');

    }

    public function getTtdUser()
    {
        if (Auth::user()->ttd != null) {
            if (Storage::exists('ttd/' . Auth::user()->ttd)) {
                $file = Storage::get('ttd/' . Auth::user()->ttd);
            } else {
                $file = Storage::get('ttd/blank.png');
            }
        } else {
            $file = Storage::get('ttd/blank.png');
        }
//        dd($file);

        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg');
//            ->header('Content-Disposition', 'attachment; filename="' . Auth::user()->foto . '"');

    }

    public function getTtd($id)
    {
        $user = User::findOrFail($id);
        if ($user->ttd != null) {
            if (Storage::exists('ttd/' . $user->ttd)) {
                $file = Storage::get('ttd/' . $user->ttd);
            } else {
                $file = Storage::get('ttd/blank.png');
            }
        } else {
            $file = Storage::get('ttd/blank.png');
        }
//        dd($file);

        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg');
//            ->header('Content-Disposition', 'attachment; filename="' . Auth::user()->foto . '"');

    }

    public function _getFotoThumb()
    {
//        dd(Storage::exists('foto-thumb/' . Auth::user()->foto));
        if (Storage::exists('foto-thumb/' . Auth::user()->foto)) {
            $file = Storage::get('foto-thumb/' . Auth::user()->foto);
        } else {
            $file = Storage::get('foto-thumb/default.jpg');
        }

//        dd($file);

        $img = Image::make($file)->resize(128, 128);

        return $img->response('jpg');

    }

    public function _getTtd($id)
    {
//        $entry = UploadData::where('filename', '=', $filename)->firstOrFail();
        $user = User::findOrFail($id);
        try {
            $file = Storage::get($user->business_area . '/ttd/' . $user->ttd);
        } catch (\Exception $e) {
            $file = file_get_contents(asset('assets/images/blank.png'));
        }


        return (new Response($file, 200))
            ->header('Content-Type', 'image/jpeg')
            ->header('Content-Disposition', 'attachment; filename="' . $user->ttd . '"');

    }

    public function updateFoto(Request $request, $id)
    {
//        dd($request);

        $file = $request->file('foto');
        if ($file == null) {
            return redirect($request->redirect)->with('warning', 'File gambar belum dipilih');
        }
        $extension = strtolower($file->getClientOriginalExtension());
        if (!($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png')) {
            return redirect($request->redirect)->with('warning', 'File yang diupload bukan gambar');
        }
        $size = $file->getSize();
        if ($size > 1000000) {
            return redirect($request->redirect)->with('warning', 'Ukuran file yang diupload melebihi 1MB');
        }

//        dd($request);

        $user = User::findOrFail($id);
//        $filename = $user->nip.''.$file->getClientOriginalName();
        $filename = $user->nip . '.' . $extension;
        $user->foto = $filename;
        $user->save();

//        Storage::put($user->business_area . '/foto/' . $filename, File::get($file));
        Storage::put('foto/' . $filename, File::get($file));

        $file_server = Storage::get('foto/' . $filename);

        // open and resize an image file
        $img = Image::make($file_server)->resize(128, 128);

        // save file as jpg with medium quality
        $img->save(storage_path('app/foto-thumb/' . $filename));
        //$img->save('foto-thumb/' . $filename, 60);
//        Storage::put('foto-thumb/' . $filename, $img->response($extension));

        Activity::log('Update foto ' . $user->name . '; ID: '.$user->id.'.', 'success');

        return redirect($request->redirect)->with('success', 'Foto profil berhasil diubah.');

    }

    public function updateTtd(Request $request, $id)
    {
//        dd($request);

        $file = $request->file('foto');
        if ($file == null) {
            return redirect($request->redirect)->with('warning', 'File gambar belum dipilih');
        }
        $extension = strtolower($file->getClientOriginalExtension());
        if (!($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png')) {
            return redirect($request->redirect)->with('warning', 'File yang diupload bukan gambar');
        }
        $size = $file->getSize();
        if ($size > 1000000) {
            return redirect($request->redirect)->with('warning', 'Ukuran file yang diupload melebihi 1MB');
        }

//        dd($request);

        $user = User::findOrFail($id);
//        $filename = $user->nip.''.$file->getClientOriginalName();
        $filename = $user->nip . '.' . $extension;
        $user->ttd = $filename;
        $user->save();

//        Storage::put($user->business_area . '/ttd/' . $filename, File::get($file));
        Storage::put('ttd/' . $filename, File::get($file));

        return redirect($request->redirect)->with('success', 'Foto tandatangan berhasil diubah.');

    }

    public function dataAjaxPemateri(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;

            if(Auth::user()->hasRole('root')) {
                $data = PA0001::with('pa0032')->where('LOWER(sname)', 'LIKE', '%' . strtolower($search) . '%')
                    ->whereIn('persg', [0, 1])
                    ->select("pernr", "sname")
                    ->take(50)
                    ->orderBy('sname', 'asc')
                    ->get();
            }
            else {
                $data = PA0001::with('pa0032')->where('LOWER(sname)', 'LIKE', '%' . strtolower($search) . '%')
                    ->whereIn('persg', [0, 1])
                    ->where('bukrs', '=', Auth::user()->company_code)
                    ->select("pernr", "sname")
                    ->take(50)
                    ->orderBy('sname', 'asc')
                    ->get();
            }
        }

        return response()->json($data);
    }

    public function dataAjaxLeader(Request $request){
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;

            if(Auth::user()->hasRole('root')) {
                $data = User::where('LOWER(name)', 'LIKE', '%' . strtolower($search) . '%')
                    ->select("nip", "name")
                    ->take(50)
                    ->orderBy('name', 'asc')
                    ->get();
            }
            else {
                $data = User::where('LOWER(name)', 'LIKE', '%' . strtolower($search) . '%')
                    ->where('company_code', '=', Auth::user()->company_code)
                    ->select("nip", "name")
                    ->take(50)
                    ->orderBy('name', 'asc')
                    ->get();
            }
        }

        return response()->json($data);
    }

    public function dataAjaxVerifikator(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = PA0001::with(['pa0032'])->where('LOWER(sname)', 'LIKE', '%' . strtolower($search) . '%')
                ->whereIn('persg', [0, 1])
                ->select("pernr", "sname")
                ->take(10)
                ->orderBy('sname', 'asc')
                ->get();
        }

        return response()->json($data);
    }

    public function getFotoFromTalent()
    {
        ini_set('max_execution_time', 36000);
//        dd(StrukturJabatan::where('nip','DP 000230')->get());

        foreach (StrukturJabatan::whereNotNull('nip')->orderBy('nip', 'desc')->get() as $pegawai) {
            if (str_replace(' ', '', trim($pegawai->nip)) != '') {
                $url = 'http://talent.pln.co.id/ecv/foto/' . str_replace(' ', '', trim(strtoupper($pegawai->nip))) . '.jpg';
//            $url = 'http://10.1.8.65/skrev/foto/'.strtoupper($pegawai->nip).'.jpg';

                $file_headers = @get_headers($url);

                if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                    echo "Error: " . $pegawai->nip . " - " . $pegawai->cname . "<br>";
                    continue;
                }
                $file = file_get_contents($url);

                echo "<img src=\"" . $url . "\" width=\"64\">" . $pegawai->nip . " - " . $pegawai->cname . "<br>";
//        dd($file);
                Storage::put('foto_pegawai/' . strtoupper($pegawai->nip) . '.jpg', $file);
            }
        }
        return 'ok';
    }

    public function editNIP()
    {
        return view('auth.edit_nip');
    }

    public function getUserAD($domain, $username)
    {

//        dd($username);
        // cek user baru di LDAP
        $domain = strtolower($domain);

        if ($domain == 'pusat') {
            $user_admin = 'simpus';
            $pass_admin = 'P@ssw0rd123#';
        } elseif ($domain == 'sutg') {
            $user_admin = 'sutg.simpus';
            $pass_admin = 'P@ssw0rdadmin2018';
        } elseif ($domain == 'bali') {
            $user_admin = 'simpusbali';
            $pass_admin = 'simpusbali123';
        } elseif ($domain == 'nad' || $domain == 'aceh') {
            $user_admin = 'aceh.simpus';
            $pass_admin = 'P@ssw0rd#123';
            $domain = 'nad';
        } elseif ($domain == 'jateng') {
            $user_admin = 'komitmen.jateng';
            $pass_admin = 'P@ssw0rd#3';
        } elseif ($domain == 'jabar') {
            $user_admin = 'komitmen.jabar';
            $pass_admin = 'P@ssw0rd#5';
        } elseif ($domain == 'jatim') {
            $user_admin = 'komitmen.jatim';
            $pass_admin = 'P@ssw0rd#4';
        } elseif ($domain == 'jaya') {
            $user_admin = 'komitmen.jaya';
            $pass_admin = 'P@ssw0rd#2';
        } elseif ($domain == 'kitsbs') {
            $user_admin = 'komitmen.kitsbs';
            $pass_admin = 'P@ssw0rd#4';
        } elseif ($domain == 'lampung') {
            $user_admin = 'komitmen.lampung';
            $pass_admin = 'P@ssw0rd#4';
        } elseif ($domain == 'ntb') {
            $user_admin = 'komitmen.ntb';
            $pass_admin = 'P@ssw0rd#5';
        } elseif ($domain == 'riau') {
            $user_admin = 'komitmen.riau';
            $pass_admin = 'P@ssw0rd#4';
        } elseif ($domain == 's2jb') {
            $user_admin = 'komitmen.s2jb';
            $pass_admin = 'P@ssw0rd#5';
        } else {
            $user_admin = 'komitmen.pusat';
            $pass_admin = 'P@ssw0rd#2';
        }

        // Construct new Adldap instance.
        $ad = new Adldap();

        // Create a configuration array.
        $config = [
            // Your account suffix, for example: jdoe@corp.acme.org
            'account_suffix' => '@' . $domain . '.corp.pln.co.id',

            // The domain controllers option is an array of your LDAP hosts. You can
            // use the either the host name or the IP address of your host.
            'domain_controllers' => [$domain . '.corp.pln.co.id'],

            // The base distinguished name of your domain.
            'base_dn' => 'DC=' . $domain . ',DC=corp,DC=pln,DC=co,DC=id',

            // The account to use for querying / modifying LDAP records. This
            // does not need to be an actual admin account.
            'admin_username' => $user_admin,
            'admin_password' => $pass_admin,

            // Optional Configuration Options
            'port' => 389,
            'follow_referrals' => false,
            'use_ssl' => false,
            'use_tls' => false,
        ];

        // Add a connection provider to Adldap.
        $ad->addProvider($config);
//        dd($ad);

        try {
            // If a successful connection is made to your server, the provider will be returned.
            $provider = $ad->connect();

            // authenticate

//            if (!$provider->auth()->attempt($username_ad, $password_ad)) {
//                return redirect('/auth/login')->with('status', 'The username or password is incorrect.');
//            }

            // Finding a record.
            $user_ad = $provider->search()->users()->where('samaccountname', '=', strtolower($username))->first();
//            dd($user_ad->getAttribute('displayname')[0]);

            if ($user_ad != null)
                return $user_ad->getAttribute('displayname')[0];
            else
                return false;

        } catch (BindException $e) {

            // There was an issue binding / connecting to the server.
//            return redirect('/auth/login')->with('status', 'Can\'t connect to Active Directory Server. Try again later or report to your administrator.');

            return false;
        }

    }

    public function updateNIP(Request $request)
    {
//        dd($request);

        // cek inputan NIP Pegawai
        $nip = trim($request->nip);
        if ($nip == '') $nip = trim($request->nip_2);

        $jab = StrukturJabatan::where('nip', $nip)->first();
        if ($jab == null)
            return redirect('auth/login')->with('warning', 'NIP tidak ditemukan atau format NIP tidak valid. Masukkan NIP tanpa spasi atau minus. Silakan coba kembali.');

//        dd($jab);
        // jika pegawai baru
        if ($request->pegawai_baru == '1') {
            $user = User::find(trim($request->user_id));
            $user->ad_employee_number = strtoupper($nip);
            $user->nip = strtoupper($nip);

            $user->business_area = $user->pa0032->pa0001->gsber;
            $user->company_code = $user->pa0032->pa0001->bukrs;

            $user->pegawai_baru = $request->pegawai_baru;
            $user->pernah_login = $request->pernah_login;
            $user->pegawai_mutasi = $request->pegawai_mutasi;
            $user->update_username = $request->update_username;

            $user->save();

            return redirect('auth/login')->with('success', 'Data user berhasil disimpan. Silakan login kembali.');
        } // jika belum pernah login
        elseif ($request->pernah_login == '0') {
            $user = User::find(trim($request->user_id));
            $user->ad_employee_number = strtoupper($nip);
            $user->nip = strtoupper($nip);

            $user->business_area = $user->pa0032->pa0001->gsber;
            $user->company_code = $user->pa0032->pa0001->bukrs;

            $user->pegawai_baru = $request->pegawai_baru;
            $user->pernah_login = $request->pernah_login;
            $user->pegawai_mutasi = $request->pegawai_mutasi;
            $user->update_username = $request->update_username;

            $user->save();

            return redirect('auth/login')->with('success', 'Data user berhasil disimpan. Silakan login kembali.');
        } // jika pegawai mutasi
        elseif ($request->pegawai_mutasi == '1') {
            // hapus username2 baru
            $user_baru = User::find(trim($request->user_id));
            $user_baru->username2 = '';
            $user_baru->save();

            $user = User::where('nip', $nip)->where('username', trim($request->username_lama))->where('domain', trim($request->domain_lama))->first();
            $user_baru = User::where('nip', $nip)->where('username', trim($request->username_baru))->where('domain', trim($request->domain_baru))->first();
            $user_ad_baru = $this->getUserAD(trim($request->domain_baru), trim($request->username_baru));
            if ($user == null) {
                return redirect('auth/login')->with('warning', 'Data user gagal ditemukan. Silakan login kembali.');
            } elseif ($user_ad_baru == false || $user_ad_baru == null) {
                return redirect('auth/login')->with('warning', 'Username: ' . trim($request->username_baru) . ' pada domain ' . trim($request->domain_baru) . ' tidak ditemukan. Silakan cek kembali inputan Anda.');
            } elseif ($user_baru != null) {
                return redirect('auth/login')->with('warning', 'Data domain dan username sudah ada. Silakan cek domain dan username baru.');
            } else {
                // update data user lama dengan data user baru
                $user->ad_employee_number = strtoupper($nip);
                $user->nip = strtoupper($nip);
                $user->username2 = strtolower(trim($request->domain_baru) . '\\' . trim($request->username_baru));
                $user->domain = strtolower(trim($request->domain_baru));
                $user->username = strtolower(trim($request->username_baru));
                // copy foto

                $user->business_area = $user->pa0032->pa0001->gsber;
                $user->company_code = $user->pa0032->pa0001->bukrs;

                $user->pegawai_baru = $request->pegawai_baru;
                $user->pernah_login = $request->pernah_login;
                $user->pegawai_mutasi = $request->pegawai_mutasi;
                $user->update_username = $request->update_username;

                $user->save();

                // delete user baru
                if ($user_baru != null) $user_baru->delete();

                return redirect('auth/login')->with('success', 'Data user berhasil disimpan. Silakan login kembali.');
            }
        } // jika ganti username
        elseif ($request->update_username == '1') {
            $user = User::where('nip', $nip)->where('username', trim($request->username_lama))->where('domain', trim($request->domain_lama))->first();
            $user_baru = User::where('nip', $nip)->where('username', trim($request->username_baru))->where('domain', trim($request->domain_baru))->first();
            $user_ad_baru = $this->getUserAD(trim($request->domain_baru), trim($request->username_baru));

            if ($user == null) {
                return redirect('auth/login')->with('warning', 'Data user gagal ditemukan. Silakan login kembali.');
            } elseif ($user_ad_baru == false || $user_ad_baru == null) {
                return redirect('auth/login')->with('warning', 'Username: ' . trim($request->username_baru) . ' pada domain ' . trim($request->domain_baru) . ' tidak ditemukan. Silakan cek kembali inputan Anda.');
            } elseif ($user_baru != null) {
                return redirect('auth/login')->with('warning', 'Data domain dan username sudah ada. Silakan cek domain dan username baru.');
            } else {
                $user->ad_employee_number = strtoupper($nip);
                $user->nip = strtoupper($nip);
                $user->username2 = strtolower(trim($request->domain_baru) . '\\' . trim($request->username_baru));
                $user->domain = strtolower(trim($request->domain_baru));
                $user->username = strtolower(trim($request->username_baru));
                // copy foto

                $user->business_area = $user->pa0032->pa0001->gsber;
                $user->company_code = $user->pa0032->pa0001->bukrs;

                $user->pegawai_baru = $request->pegawai_baru;
                $user->pernah_login = $request->pernah_login;
                $user->pegawai_mutasi = $request->pegawai_mutasi;
                $user->update_username = $request->update_username;

                $user->save();

                return redirect('auth/login')->with('success', 'Data user berhasil disimpan. Silakan login kembali.');
            }
        } // default
        else {
            $user = User::where('nip', $nip)->where('username', $request->username)->first();

            if ($user == null) {
                return redirect('auth/login')->with('warning', 'Data user gagal ditemukan. Silakan login kembali.');
            } else {
                $user->ad_employee_number = strtoupper($nip);
                $user->nip = strtoupper($nip);
                $user->username2 = strtolower(trim($request->domain) . '\\' . trim($request->username));
                $user->domain = strtolower(trim($request->domain));
                // copy foto

                $user->business_area = $user->pa0032->pa0001->gsber;
                $user->company_code = $user->pa0032->pa0001->bukrs;

                $user->pegawai_baru = $request->pegawai_baru;
                $user->pernah_login = $request->pernah_login;
                $user->pegawai_mutasi = $request->pegawai_mutasi;
                $user->update_username = $request->update_username;

                $user->save();

                return redirect('auth/login')->with('success', 'Data user berhasil disimpan. Silakan login kembali.');
            }
        }
//        // cek inputan NIP Pegawai
//        $jab = StrukturJabatan::where('nip',$request->nip)->first();
//        if($jab==null)
//            return redirect('auth/login')->with('warning', 'NIP tidak ditemukan atau format NIP tidak valid. Masukkan NIP tanpa spasi atau minus. Silakan coba kembali.');
//
//        $user = User::find($request->user_id);
//        $user->ad_employee_number = strtoupper($request->nip);
//        $user->nip = strtoupper($request->nip);
////        $user->save();
//
//        //update business_area
//        $user->business_area = $user->pa0032->pa0001->gsber;
//        $user->company_code = $user->pa0032->pa0001->bukrs;
//        $user->save();
////        $strukjab = StrukturJabatan::where('nip', $user->nip)->first();
////        if($strukjab!=null) {
////            $strukjab->email = $user->email;
////            $strukjab->save();
////        }
//
//        return redirect('auth/login')->with('success', 'NIP berhasil disimpan. Silakan login kembali.');
//
////        dd($request);
    }

    public function migrateAdminUnit()
    {
        $role_id = 4;
        $role = Role::find($role_id);
//        $role = RoleProduction::find(6);
//        dd($role->users);
        foreach ($role->users as $user) {
            $user_prod = UserProduction::where('nip', $user->nip)->first();
            if ($user_prod != null) {
//                echo @$user->nip . '/' . @$user_prod->nip . ' : ' . @$user->name . '/' . @$user_prod->name . '<br>';
                echo @$user_prod->nip . ' : ' . @$user_prod->name . '<br>';
                $user_roles = $user_prod->roles;

                // jika user sudah punya role
                if ($user_roles != null) {
                    echo 'Sudah punya role:<br>';
                    $has_role = false;
                    $has_pegawai = false;
                    foreach ($user_roles as $user_role) {
                        echo '-' . $user_role->name . '<br>';
                        if ($user_role->id == $role_id)
                            $has_role = true;
                        if ($user_role->id == '5')
                            $has_pegawai = true;
                    }
                    if ($has_role == false) {
                        //attach role
                        $user_prod->roles()->attach($role_id);
                    }
                    if ($has_pegawai == false) {
                        // attach pegawai
                        $user_prod->roles()->attach(5);
                    }
                } // jika user belum punya role
                else {
                    echo 'Belum punya role<br>';
                    $user_prod->roles()->attach(5);
                    $user_prod->roles()->attach($role_id);
                }
            } else {
                $user_new = new UserProduction();
                $user_new->username = $user->username;
                $user_new->username2 = $user->username2;
                $user_new->name = $user->name;
                $user_new->email = $user->email;
                $user_new->password = $user->password;
//                $user_new->active_directory = $user->active_directory;
                $user_new->ad_display_name = $user->ad_display_name;
                $user_new->ad_mail = $user->ad_mail;
                $user_new->ad_company = $user->ad_company;
                $user_new->ad_department = $user->ad_department;
                $user_new->ad_title = $user->ad_title;
                $user_new->ad_employee_number = $user->ad_employee_number;
                $user_new->company_code = $user->company_code;
                $user_new->business_area = $user->business_area;
                $user_new->status = $user->status;
                $user_new->domain = $user->domain;
                $user_new->nip = $user->nip;

//                dd($user_new);
                $user_new->save();
                echo 'New User: ' . @$user->nip . ' : ' . @$user->name . '<br>';
            }
            echo '<br>';
        }
//        return 'Admin Unit';
    }

    public function updateCompanyCode()
    {
        $users = User::whereNull('company_code')->get();
        foreach ($users as $user) {
//            if ($user->nip == '') {
//                $user->nip = $user->ad_employee_number;
//                $user->save();
//            }
//            if($user->nip!='') {
            if ($user->pa0032 != null) {
                $bukrs = $user->pa0032->pa0001->bukrs;
                $gsber = $user->pa0032->pa0001->gsber;
                echo $user->nip . ' | ' . $bukrs . ' | ' . $gsber . '<br>';
                $user->company_code = $bukrs;
                $user->business_area = $gsber;
                $user->save();
            }
//            }
        }

//        return 'OK';
    }

    public function updateNIPMass()
    {
        $users = User::whereNull('nip')->get();
        foreach ($users as $user) {
            if ($user->strukturJabatan != null) {
                $user->nip = $user->strukturJabatan->nip;
//                $user->nip = $user->ad_employee_number;
                $user->save();
                echo $user->nip . '<br>';
            }
//            if($user->nip!='') {
//            if ($user->pa0032 != null) {
//                $bukrs = $user->pa0032->pa0001->bukrs;
//                $gsber = $user->pa0032->pa0001->gsber;
//                echo $user->nip . ' | ' . $bukrs . ' | ' . $gsber . '<br>';
//                $user->company_code = $bukrs;
//                $user->business_area = $gsber;
//                $user->save();
//            }
//            }
        }

//        return 'OK';
    }

    public function updateNIPFromAD()
    {
        $users = User::whereNull('nip')->get();
        foreach ($users as $user) {
            if ($user->ad_employee_number != null) {
                $user->nip = $user->ad_employee_number;
//                $user->nip = $user->ad_employee_number;
                $user->save();
                echo $user->nip . '<br>';
            }
//            if($user->nip!='') {
//            if ($user->pa0032 != null) {
//                $bukrs = $user->pa0032->pa0001->bukrs;
//                $gsber = $user->pa0032->pa0001->gsber;
//                echo $user->nip . ' | ' . $bukrs . ' | ' . $gsber . '<br>';
//                $user->company_code = $bukrs;
//                $user->business_area = $gsber;
//                $user->save();
//            }
//            }
        }

//        return 'OK';
    }

    public function cekUser()
    {
        $nip_err = ['6393330B', '6484090P', '6492003B', '6388030B', '6592055Z', '6593423B', '6594237B', '6595100B', '6694987JA', '6693075B', '6693179B', '6694093B', '6792093Z', '6793410B', '6794184B', '6794190B', '6795043B', '6890225R', '6890003S', '6893342B', '6893350B', '6894008P2B', '6993204Z', '6993226Z', '6995049B', '6994003F', '7093435B', '7093531B', '7094065B', '7094227B', '7095010B', '7095085B', '7094258B', '7095014F', '7193308B', '7193343B', '7193433B', '7193436B', '7195068B', '7293396B', '7293417B', '7294024B', '7294028B', '7294029B', '7294222B', '7294091E', '7295017W', '7295088R', '7295091R', '7295092R', '7393345B', '7502002B2', '7502002B4', '7604008B2', '770221096I', '7702020B2', '7805014B2', '7806040Z', '7902005B2', '7905006B2', '7905007B2', '7905008B2', '7906134Z', '7906515Z', '8004002B3', '8005005B2', '8006221Z', '8006245Z', '8006206Z', '8007077Z', '8008030Z', '8106353Z', '8106584Z', '8108059Z', '8206427Z', '8206431Z', '8207218Z', '8207212Z', '8207194Z', '8208101Z', '8209573Z', '8209563Z', '8309405Z', '8309582Z', '8309179Z', '8309158Z', '8407017CLG', '8408001B2', '8408374Z', '8408077B2', '8409210Z', '8410557Z', '8508059B2', '8508064B2', '8509275Z', '8509326Z', '8509260Z', '8510096Z', '85112293Z', '8511848Z', '8608060B2', '8608062B2', '8608063B2', '8609438Z', '8609366Z', '8611858Z', '8708056B2', '8708058B2', '8710012B2', '8710014B2', '8710021B2', '8710008CLG', '87112202Z', '87111876Z', '8711550Z', '8711565Z', '8712310ZY', '8809032B2', '8810011B2', '8810736Z', '8811701Z', '8812072ZY', '8909037B2', '8909008B2', '8910040B2', '8910025B2', '9009034B2', '9009035B2', '9009026B2', '9009041B2', '9009028B2', '9010024B2', '90112024Z', '90163016ZY', '9116402ZY', '92161362ZY', '93163246ZY', '93173985ZY', '94163477ZY', '9717019KBY', '9917089KBY'];

        foreach ($nip_err as $nip) {
            $user = User::where('nip', $nip)->first();

            if ($user == null) {
//                echo 'NIP tidak ditemukan : '.$nip.'<br>';
                echo $nip . '<br>';
            } else {
//                echo $user->nip.' : '.$user->name.'<br>';
            }
        }
//        dd($nip_err);
    }

    public function cekUserRole()
    {
        $users = User::all();
        foreach ($users as $user) {
            $role_pegawai = $user->roles()->where('id', '5')->first();
            if ($role_pegawai == null) {
                echo $user->nip . ' : ' . $user->name . '<br>';
                $user->roles()->attach(5);
            }
        }
    }

    public function cekEmailDuplicate()
    {
        $strukjab = StrukturJabatan::whereNotNull('email')->get();
        foreach ($strukjab as $user) {
            $search = StrukturJabatan::where('email', $user->email)->get();
            if ($search->count() > 1) {
                foreach ($search as $usr) {
                    echo $usr->nip . ' - ' . $usr->cname . ' : ' . $usr->email . '<br>';
                }
                echo '<br>';
            }
        }
    }

    public function cekWrongNIP()
    {
        $users = User::whereNotNull('ad_employee_number')->get();
        foreach ($users as $user) {
            if ($user->nip != $user->ad_employee_number) {
                echo $user->nip . ' - ' . $user->ad_employee_number . '<br>';
            }
        }
    }

    public function resolveWrongNIP()
    {
        $users = User::whereNotNull('ad_employee_number')->get();
        foreach ($users as $user) {
            if ($user->nip != $user->ad_employee_number) {
                echo $user->nip . ' - ' . $user->ad_employee_number . '<br>';
                $user->nip = $user->ad_employee_number;
                $user->save();
                $pa0001 = $user->strukturJabatan->pa0001()->first();
                $user->business_area = $pa0001->bukrs;
                $user->company_code = $pa0001->gsber;
                $user->save();
            }
        }
    }

    public function updatePersg()
    {
        $strukjab = StrukturJabatan::whereNotNull('email')->get();
        foreach ($strukjab as $user) {
            $persg = PA0001::where('pernr', $user->pernr)
                ->where('mandt', '100')
                ->where('endda', '99991231')
                ->first()
                ->persg;
            $user->persg = $persg;
            $user->save();
        }
        return 'FINISH';
    }

    public function deleteEmailPurna()
    {
        $strukjab = StrukturJabatan::whereNotNull('email')->get();
        foreach ($strukjab as $user) {
            if ($user->persg != '1') {
                $user->email = '_' . $user->email;
                $user->save();
            }
        }
        return 'FINISH';
    }

    public function convertImage()
    {
        $user_list = User::whereNotNull('foto')->orderBy('id', 'asc')->get();
        foreach ($user_list as $user) {
            echo $user->id . '|' . $user->business_area . '|' . $user->foto . '<br>';

            try {
                // cek file
                if (Storage::disk('ftp_plnpusat')->exists('app/' . $user->business_area . '/foto/' . $user->foto)) {
                    // jika ada

//                echo 'ada<br>';

                    $file_server = Storage::disk('ftp_plnpusat')->get('app/' . $user->business_area . '/foto/' . $user->foto);

                    $extension = explode('.', $user->foto);
                    $extension = strtolower($extension[count($extension) - 1]);
                    $filename = $user->nip . '.' . $extension;

                    if (!Storage::disk('ftp_plnpusat')->exists('app/foto-conv/' . $filename)) {

                        // open and resize an image file
                        $img = Image::make($file_server)->resize(128, 128);
                        $img->save(storage_path('app/foto-thumb-conv/' . $filename));
                        $img2 = Image::make($file_server);
                        $img2->save(storage_path('app/foto-conv/' . $filename));

                        $user->foto_tmp = $filename;
                        $user->save();

                        // make thumbnail
//                        Storage::disk('ftp_plnpusat')->put('app/foto-thumb-conv/' . $filename, $img->response($extension));
                        // copy ke foto
//                        Storage::disk('ftp_plnpusat')->put('app/foto-conv/' . $filename, $img2->response($extension));
                    } else {
                        echo 'ERROR : FILE EXIST <br>';
                    }
                } else {
                    // jika tidak ada reset foto
//                $user->foto = '';
//                $user->save();
                    echo 'ERROR : FILE NOT FOUND <br>';
                }
            } catch (FileNotFoundException $e) {
                echo 'ERROR : ' . $e->getMessage() . ' <br>';
                continue;
            } catch (NotReadableException $e) {
                echo 'ERROR : ' . $e->getMessage() . ' <br>';
                continue;
            } catch (Exception $e) {
                echo 'ERROR : ' . $e->getMessage() . ' <br>';
                continue;
            }


        }
    }

    public function convertImageTTD()
    {
        $user_list = User::whereNotNull('ttd')->get();
        foreach ($user_list as $user) {
            echo $user->business_area . '|' . $user->ttd . '<br>';
            // cek file
            try {
                // jika ada


                $file_server = Storage::get($user->business_area . '/ttd/' . $user->ttd);

                $extension = explode('.', $user->foto);
                $extension = $extension[count($extension) - 1];
                $filename = $user->nip . '.' . $extension;

                // open and resize an image file
//                $img = Image::make($file_server)->resize(128, 128);
                $img2 = Image::make($file_server);

                // make thumbnail
//                Storage::put('foto-thumb/' . $filename, $img->response($extension));
                // copy ke foto
                Storage::put('ttd/' . $filename, $img2->response($extension));
            } catch (\Exception $e) {
                // jika tidak ada reset foto
//                $user->foto = '';
//                $user->save();
                echo "ERROR : " . $e->getMessage() . " <br>";
            }


        }
    }

    public function convertUsername()
    {
        $users = User::where('username', '!=', 'lower(username)')->get();

        return $users->count();
    }

    public function updateActivityLog()
    {
        $users = User::all();
        foreach ($users as $user) {
            echo $user->username2 . ' : <br>';
            foreach ($user->activitiesUsername as $act) {
                $act->user_id = $user->id;
                $act->save();
                echo $act->text . ' | ' . $user->id . '<br>';
            }
            echo '<br>';
        }
        return 'FINISH';
    }

    public static function updateReadMateri()
    {
        $users = User::all();
        foreach ($users as $user) {
            echo $user->username2 . ' : <br>';
            foreach ($user->readMateriUsername as $read) {
                $read->user_id = $user->id;
                $read->save();
                echo $read->materi_id . ' | ' . $user->id . '<br>';
            }
            echo '<br>';
        }
        return 'FINISH';
    }

    public function updateTema()
    {
//        $users = User::all();
        $role = Role::find(3);
        foreach ($role->users as $user) {
            echo $user->username2 . ' : <br>';
            foreach ($user->createTemaUsername as $tema) {
                $tema->user_id_create = $user->id;
                $tema->user_id_update = $user->id;
                $tema->save();
                echo $tema->id . ' | ' . $user->id . '<br>';
            }
            echo '<br>';
        }
        return 'FINISH';
    }

    public function updateDescription($domain)
    {
        if ($domain == 'pusat') {
            $user_admin = 'simpus';
            $pass_admin = 'P@ssw0rd123#';
        } elseif ($domain == 'sutg') {
            $user_admin = 'sutg.simpus';
            $pass_admin = 'P@ssw0rdadmin2018';
        } elseif ($domain == 'bali') {                          // done
            $user_admin = 'simpusbali';
            $pass_admin = 'simpusbali123';
        } elseif ($domain == 'nad' || $domain == 'aceh') {      // done
            $user_admin = 'aceh.simpus';
            $pass_admin = 'P@ssw0rd#123';
            $domain = 'nad';
        } elseif ($domain == 'jateng') {                        // done
            $user_admin = 'komitmen.jateng';
            $pass_admin = 'P@ssw0rd#3';
        } elseif ($domain == 'jabar') {                         // done
            $user_admin = 'komitmen.jabar';
            $pass_admin = 'P@ssw0rd#5';
        } elseif ($domain == 'jatim') {                         // done
            $user_admin = 'komitmen.jatim';
            $pass_admin = 'P@ssw0rd#4';
        } elseif ($domain == 'jaya') {                          // done
            $user_admin = 'komitmen.jaya';
            $pass_admin = 'P@ssw0rd#2';
        } elseif ($domain == 'kitsbs') {                        // done
            $user_admin = 'komitmen.kitsbs';
            $pass_admin = 'P@ssw0rd#4';
        } elseif ($domain == 'lampung') {                       // done
            $user_admin = 'komitmen.lampung';
            $pass_admin = 'P@ssw0rd#4';
        } elseif ($domain == 'ntb') {                           // done
            $user_admin = 'komitmen.ntb';
            $pass_admin = 'P@ssw0rd#5';
        } elseif ($domain == 'riau') {                          // done
            $user_admin = 'komitmen.riau';
            $pass_admin = 'P@ssw0rd#4';
        } elseif ($domain == 's2jb') {                          // done
            $user_admin = 'komitmen.s2jb';
            $pass_admin = 'P@ssw0rd#5';
        } else {
            $user_admin = 'komitmen.pusat';
            $pass_admin = 'P@ssw0rd#2';
        }

        // Construct new Adldap instance.
        $ad = new Adldap();

        // Create a configuration array.
        $config = [
            // Your account suffix, for example: jdoe@corp.acme.org
            'account_suffix' => '@' . $domain . '.corp.pln.co.id',

            // The domain controllers option is an array of your LDAP hosts. You can
            // use the either the host name or the IP address of your host.
            'domain_controllers' => [$domain . '.corp.pln.co.id'],

            // The base distinguished name of your domain.
            'base_dn' => 'DC=' . $domain . ',DC=corp,DC=pln,DC=co,DC=id',

            // The account to use for querying / modifying LDAP records. This
            // does not need to be an actual admin account.
            'admin_username' => $user_admin,
            'admin_password' => $pass_admin,

            // Optional Configuration Options
            'port' => 389,
            'follow_referrals' => false,
            'use_ssl' => false,
            'use_tls' => false,
        ];

        // Add a connection provider to Adldap.
        $ad->addProvider($config);

//        $provider = $ad->connect();

//        dd($ad);

        try {
            // If a successful connection is made to your server, the provider will be returned.
            $provider = $ad->connect();

//            dd($provider);

            $user_list = User::where('domain', $domain)->whereNotNull('nip')->whereNull('ad_dn')->whereNull('ad_description')->orderBy('id', 'asc')->take(5000)->get();
//            $user_list = User::where('domain', $domain)->whereNotNull('nip')->whereNull('ad_dn')->whereNull('ad_description')->orderBy('id', 'asc')->get();
//            dd($user_list->count());

            foreach ($user_list as $user) {
                echo $user->name . '|' . $user->nip . '|' . $user->username2 . '|';
                // Finding a record.
                $user_ad = $provider->search()->users()->where('samaccountname', '=', $user->username)->first();
                if ($user_ad != null) {
                    $user->ad_description = $user_ad->getAttribute('description')[0];
                    $user->ad_dn = $user_ad->getAttribute('dn');
                    echo $user->ad_description . '|' . $user->ad_dn . "<br>";
                    $user->save();
                } else {
                    echo 'ERROR: SAMACCOUNTNAME NOT FOUND<br>';
                    $user->ad_description = 'ERROR: SAMACCOUNTNAME NOT FOUND';
                    $user->ad_dn = '';
                    $user->save();
                }
//                dd($user_ad);
            }

        } catch (BindException $e) {

            // There was an issue binding / connecting to the server.
            return redirect('/auth/login')->with('status', 'Can\'t connect to Active Directory Server. Try again later or report to your administrator.');

        }

        echo "FINISH";
    }

    public function cleanDataUser()
    {
        $pegawai_list = PegawaiAktif::orderBy('pernr', 'asc')->take(100)->get();

        foreach ($pegawai_list as $pegawai) {
            echo $pegawai->nip . ' | ' . $pegawai->name . ' | ' . @$pegawai->user->ad_description . '<br>';
        }
    }

    public function updateFileFoto()
    {
        $user_list = User::whereNotNull('foto')->orderBy('id', 'asc')->get();
        foreach ($user_list as $user) {

            $extension = explode('.', $user->foto);
            $extension = strtolower($extension[count($extension) - 1]);
            $filename = $user->nip . '.' . $extension;
            $user->foto_tmp = $filename;
            $user->save();

            echo $user->id . '|' . $user->business_area . '|' . $user->foto . '|' . $user->foto_tmp . '<br>';

        }
    }

    public function storeSuhu(Request $request){

        $suhu = SuhuBadan::where('user_id', Auth::user()->id)
            ->whereDate('tanggal', '=', date('Y-m-d'))
            ->where('status', 'ACTV')
            ->first();

        if($suhu==null) {

            $suhu = new SuhuBadan();
            $suhu->user_id = Auth::user()->id;
            $suhu->suhu = $request->suhu;
            $suhu->keterangan = $request->keterangan;
            $suhu->tanggal = Carbon::now();
            $suhu->save();

            if($request->suhu >= 38)
                return redirect()->back()->with('warning','Suhu tubuh Anda lebih dari 38 derajat celcius. Silakan periksakan diri Anda ke dokter.');

            return redirect()->back()->with('success','Data suhu badan berhasil disimpan. Untuk melihat history suhu badan, silakan buka halaman profil.');

        }
        else{

            $suhu->suhu = $request->suhu;
            $suhu->keterangan = $request->keterangan;
            $suhu->tanggal = Carbon::now();
            $suhu->save();

            if($request->suhu >= 38)
                return redirect()->back()->with('warning','Suhu tubuh Anda lebih dari 38 derajat celcius. Silakan periksakan diri Anda ke dokter.');

            return redirect()->back()->with('success','Data suhu badan hari ini sudah ada. Data berhasil diubah.');

        }
    }
}
