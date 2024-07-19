<?php

namespace App;

use App\Enum\LiquidPermission;
use App\Models\Liquid\LiquidPeserta;
use App\Models\Traits\UserAccessor;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Services\LiquidService;
use App\Enum\RolesEnum;
use App\Enum\LiquidMenuEnum;
use App\Models\Liquid\ActivityLogBook;
use App\Models\Liquid\Liquid;

class User extends Authenticatable
{
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'username2',
        'name',
        'email',
        'password',
        'active_directory',
        'ad_display_name',
        'ad_mail',
        'ad_company',
        'ad_department',
        'ad_title',
        'ad_employee_number',
        'company_code',
        'business_area',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'active_directory' => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function activitiesUsername()
    {
        return $this->hasMany('App\Activity', 'username', 'username')->orderBy('id', 'desc');
    }

    public function activities()
    {
        return $this->hasMany('App\Activity', 'user_id', 'id')->orderBy('id', 'desc');
    }

    public function activityLogs()
    {
        return $this->hasMany('App\Activity', 'user_id', 'id')->take(6)->orderBy('id', 'desc');
    }

    public function companyCode()
    {
        return $this->belongsTo('App\CompanyCode', 'company_code', 'company_code');
    }

    public function businessArea()
    {
        return $this->belongsTo('App\BusinessArea', 'business_area', 'business_area');
    }

    public function uploadData()
    {
        return $this->hasMany('App\UploadData', 'username', 'username');
    }

    public function suhuBadan(){
        return $this->hasMany('App\SuhuBadan','user_id','id');
    }

    public function cekSuhuBadan(){
        $suhu = SuhuBadan::where('user_id', $this->id)
            ->whereDate('tanggal', '=', date('Y-m-d'))
            ->where('status', 'ACTV')
            ->first();

        if($suhu==null){
            return true;
        }

        return false;

    }

    public function setActiveDirectoryAttribute($attrValue)
    {
//        $this->active_directory = $attrValue;
        $this->attributes['active_directory'] = (string)$attrValue;
    }

    public function setIdAttribute($attrValue)
    {
//        $this->active_directory = $attrValue;
        $this->attributes['id'] = (string)$attrValue;
    }

    public function strukturJabatanSAP()
    {
//        return $this->hasOne('App\StrukturJabatan', 'email', 'email');
        return $this->hasOne('App\StrukturJabatanSAP', 'nip', 'nip');
    }

    public function strukturJabatan()
    {
//        return $this->hasOne('App\StrukturJabatan', 'email', 'email');
        return $this->hasOne('App\StrukturJabatan', 'nip', 'nip');
    }

    public function strukturOrganisasi()
    {
        return @$this->strukturJabatan->strukturOrganisasi;
    }

    public function organisasi(){
        return $this->hasOne('App\StrukturOrganisasi', 'objid', 'orgeh');
    }

    public function strukturPosisi()
    {
        // return $this->strukturJabatan->strukturPosisi;
        return $this->hasOne('App\StrukturPosisi', 'objid', 'plans');
    }

    public function getPohonOrganisasi()
    {
        // $organisasi = $this->strukturOrganisasi();
        $organisasi = $this->organisasi;
    //    dd($organisasi);
//
        if($organisasi!=null)
            return $organisasi->getAncestor();
        else {
            return null;
        }
    }

    public function getStatusNonAktif(){
        $pa0001 = $this->pa0032->pa0001;
    }

    public function getDivisi()
    {
        $posisi = $this->strukturPosisi();
        if($posisi!=null){
            if(starts_with($posisi->stxt2, 'DIV') ||
                starts_with($posisi->stxt2, 'DIT') ||
                starts_with($posisi->stxt2, 'IPOD') ||
                starts_with($posisi->stxt2, 'IPAD') ||
                starts_with($posisi->stxt2, 'SETPER') ||
                starts_with($posisi->stxt2, 'SPI') ||
                starts_with($posisi->stxt2, 'DEP') ||
                starts_with($posisi->stxt2, '(ANAK PERUSAHAAN)')){
                return $posisi->stxt2;
            }
        }

        $organisasi = $this->strukturOrganisasi();
        if($organisasi!=null)
            return $organisasi->getDivisi();
        else
            return null;
    }

    public function getKodeDivisi()
    {
        $posisi = $this->strukturPosisi;
        if($posisi!=null){
            if(starts_with($posisi->stxt2, 'DIV') ||
                starts_with($posisi->stxt2, 'DIT') ||
                starts_with($posisi->stxt2, 'IPOD') ||
                starts_with($posisi->stxt2, 'IPAD') ||
                starts_with($posisi->stxt2, 'SETPER') ||
                starts_with($posisi->stxt2, 'SPI') ||
                starts_with($posisi->stxt2, 'DEP') ||
                starts_with($posisi->stxt2, '(ANAK PERUSAHAAN)')){
                return $posisi->sobid;
            }
        }

        $organisasi = $this->strukturOrganisasi();
        if($organisasi!=null)
            return $organisasi->getKodeDivisi();
        else
            return null;
    }

    public function getKodeDivisiPusat()
    {
        $pegawai = DB::table('v_snapshot_pegawai')->where('pernr', $this->strukturJabatan->pernr)->first();
        if (!$pegawai) {
            return $this->getKodeDivisi();
        }

        $listDivisiPusat = app(LiquidService::class)->listDivisiPusat()->keys()->toArray();

        if (in_array($pegawai->orgeh1, $listDivisiPusat)) {
            return $pegawai->orgeh1;
        }

        if (in_array($pegawai->orgeh2, $listDivisiPusat)) {
            return $pegawai->orgeh2;
        }

        if (in_array($pegawai->orgeh3, $listDivisiPusat)) {
            return $pegawai->orgeh3;
        }

        if (in_array($pegawai->orgeh4, $listDivisiPusat)) {
            return $pegawai->orgeh4;
        }

        return $this->getKodeDivisi();
    }

    public function perilakuPegawai()
    {
        return $this->hasMany('App\PerilakuPegawai', 'user_id', 'id');
    }

    public function perilakuPegawaiTahunIni()
    {
        return $this->hasMany('App\PerilakuPegawai', 'user_id', 'id')->where('tahun', date('Y'))->get();
    }

    public function perilakuPegawaiTahun($tahun)
    {
        return $this->hasMany('App\PerilakuPegawai', 'user_id', 'id')->where('tahun', $tahun)->get();
    }

    public function jawabanPegawai()
    {
        return $this->hasMany('App\JawabanPegawai', 'user_id', 'id');
    }

    public function komitmenPegawai()
    {
        return $this->hasMany('App\KomitmenPegawai', 'user_id', 'id');
    }

    public function getKomitmenTahunini()
    {
        return $this->hasMany('App\KomitmenPegawai', 'user_id', 'id')->where('tahun', date('Y'))->first();
    }

    public function getKomitmenTahun($tahun)
    {
        return $this->hasMany('App\KomitmenPegawai', 'user_id', 'id')->where('tahun', $tahun)->first();
    }

    public function cekBelumTandaTangan($tahun = '')
    {
        if ($tahun == '') $tahun = date('Y');

        $komitmen_tahun_ini = Auth::user()->komitmenPegawai()->where('tahun', $tahun)->first();
//        $jabatan_pegawai = Auth::user()->strukturJabatan;

//        dd($komitmen_tahun_ini);

        // jika tahun ini belum ttd komitmen
        if ($komitmen_tahun_ini == null) {
            return true;
        } // jika tahun ini sdh ttd komitmen
        else {
//            dd($komitmen_tahun_ini->orgeh);
            //jika mutasi
//                if ($komitmen_tahun_ini->orgeh != $jabatan_pegawai->orgeh) {
//                    return true;
//                } else {
//                    return false;
//                }
            return false;
        }
    }

    public function getOrgLevel()
    {
        // if strukturOrganisasi is not null
        if($this->strukturOrganisasi()!=null)
          return $this->strukturOrganisasi()->getOrgLevel();
        else{
            // get struktur organisasi from orgeh user
            $organisasi = StrukturOrganisasi::where('objid', $this->orgeh)->first();
            return $organisasi->getOrgLevel();
        }

    }

    public function getOrgText()
    {
        if (isset($this->strukturJabatan->strukturOrganisasi)) {
            return $this->strukturJabatan->strukturOrganisasi->stext;
        }

        return null;
    }

    public function getArrOrgLevel()
    {
        // if($this->strukturOrganisasi()!=null)
        //     return $this->strukturOrganisasi()->getArrOrgLevel();
        // else{
            // get stuktut organisasi from orgeh user
            $organisasi = StrukturOrganisasi::where('objid', $this->orgeh)->first();
            return $organisasi->getArrOrgLevel();
        // }
            
    }

    public function attendant()
    {
        return $this->hasMany('App\Attendant', 'user_id', 'id');
    }

    public function readMateriUsername()
    {
        return $this->hasMany('App\ReadMateri', 'username', 'username');
//        return $this->hasMany('App\ReadMateri', 'user_id', 'id');
    }

    public function readMateri()
    {
        return $this->hasMany('App\ReadMateri', 'user_id', 'id');
    }

    public function hasReadMateri($materi_id)
    {
        $read = $this->readMateri()->where('materi_id', $materi_id)->first();

        if ($read != null) return $read;
        else false;
    }

    public function coc()
    {
        return $this->hasMany('App\Coc', 'admin_id', 'id');
    }

    public function cocOpen()
    {
        return $this->hasMany('App\Coc', 'admin_id', 'id')->where('status', 'OPEN');
    }

    public function cocComp()
    {
        return $this->hasMany('App\Coc', 'admin_id', 'id')->where('status', 'COMP');
    }

    public function hasReadMateriCoc($materi_id, $coc_id)
    {
        $read = $this->readMateri()->where('materi_id', $materi_id)->where('coc_id', $coc_id)->first();

        if ($read != null) return $read;
        else false;
    }

    public function pa0032()
    {
        return $this->hasOne('App\PA0032', 'nip', 'nip');
    }

    public function setNipAttribute($attrValue){
        $this->attributes['nip'] = (string) $attrValue;
    }

    public function getNipAttribute($attrValue){
       return (string) $attrValue;
    }

    public function isGM()
    {
        $pa0001 = $this->pa0032->pa0001;
//        dd($pa0001);
//        $plans = PA0001::where('mandt', '100')
//            ->where('endda', '99991231')
//            ->where('bukrs', $company_code)
//            ->where('plans', '!=', '99999999')
//            ->where('plans', '!=', '00000000')
//            ->whereIn('btrtl', $btrtls)
//            ->get(['plans'])->toArray();

        $hrp1513 = $pa0001->hrp1513()->where('endda', '99991231')->first();
        if($hrp1513==null)
            $hrp1513 = $pa0001->hrp1513()->orderBy('endda', 'desc')->first();
//        dd($hrp1513);

        if ($hrp1513->mgrp == '04' && $hrp1513->sgrp == '01')
            return true;
        else
            return false;

//        $hrp1513 = HRP1513::where('mandt', '100')
//            ->where('endda', '99991231')
//            ->where('mgrp','04')
//            ->where('sgrp', '01')
//            ->where('objid', $pa0001->plans)
////            ->get(['objid'])->toArray();
//            ->get();
//
//        dd($hrp1513);

    }

    public function getJenjangId()
    {
        $pa0001 = $this->pa0032->pa0001;
//        dd($pa0001);
//        $plans = PA0001::where('mandt', '100')
//            ->where('endda', '99991231')
//            ->where('bukrs', $company_code)
//            ->where('plans', '!=', '99999999')
//            ->where('plans', '!=', '00000000')
//            ->whereIn('btrtl', $btrtls)
//            ->get(['plans'])->toArray();

        $hrp1513 = $pa0001->hrp1513()->where('endda', '99991231')->first();
        if($hrp1513==null)
            $hrp1513 = $pa0001->hrp1513()->orderBy('endda', 'desc')->first();
//        dd($hrp1513);

        $level = $this->getOrgLevel();

        if ($hrp1513->mgrp == '04' && $hrp1513->sgrp == '01' && $level == '1')
            $jabatan_id = '1';
        elseif ($hrp1513->mgrp == '04' && $hrp1513->sgrp == '02' && $level == '1')
            $jabatan_id = '2';
        elseif ($hrp1513->mgrp == '04' && $hrp1513->sgrp == '03' && $level == '1')
            $jabatan_id = '3';
        elseif ($hrp1513->mgrp == '04' && $hrp1513->sgrp == '03' && $level == '2')
            $jabatan_id = '4';
        elseif ($hrp1513->mgrp == '04' && $hrp1513->sgrp == '04' && $level == '2')
            $jabatan_id = '5';
        elseif ($hrp1513->mgrp == '04' && $hrp1513->sgrp == '04' && $level == '3')
            $jabatan_id = '6';
        else
            $jabatan_id = '7';

        return $jabatan_id;

//        if($jabatan_id==1 && $jabatan->level==1) $sgrp = '01';          // GM
//        elseif ($jabatan_id==2 && $jabatan->level==1) $sgrp = '02';     // MB
//        elseif ($jabatan_id==3 && $jabatan->level==1) $sgrp = '03';     // DM
//        elseif ($jabatan_id==4 && $jabatan->level==2) $sgrp = '03';     // Man Area
//        elseif ($jabatan_id==5 && $jabatan->level==2) $sgrp = '04';     // Asman
//        elseif ($jabatan_id==6 && $jabatan->level==3) $sgrp = '04';     // Man Rayon

//        $hrp1513 = HRP1513::where('mandt', '100')
//            ->where('endda', '99991231')
//            ->where('mgrp','04')
//            ->where('sgrp', '01')
//            ->where('objid', $pa0001->plans)
////            ->get(['objid'])->toArray();
//            ->get();
//
//        dd($hrp1513);

    }

//    public static function convertUsername(){
//        $users = User::where('username', '!=', 'lower(username)')->get();
//
//        foreach($users as $user){
//            echo $user->username.'<br>';
//        }
//
//        return $users->count();
//    }

    public function createTemaUsername()
    {
        return $this->hasMany('App\TemaCoC', 'created_by', 'username');
    }

    public function createTema()
    {
        return $this->hasMany('App\TemaCoC', 'user_id_create', 'id');
    }

    public function updateTema()
    {
        return $this->hasMany('App\TemaCoC', 'user_id_update', 'id');
    }

    public static function updateCompanyCode()
    {
        $users = User::all();
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

    public function notifications()
    {
        return $this->hasMany('App\Notification', 'user_id_to', 'id');
    }

    public static function convertImage()
    {
        $user_list = User::whereNotNull('foto')->orderBy('id', 'desc')->take(10)->get();
        foreach ($user_list as $user) {
            echo $user->business_area . '|' . $user->foto . '<br>';
            // cek file
            if (Storage::disk('ftp_plnpusat')->exists('app/' . $user->business_area . '/foto/' . $user->foto)) {
                // jika ada

//                echo 'ada<br>';

                $file_server = Storage::disk('ftp_plnpusat')->get('app/' . $user->business_area . '/foto/' . $user->foto);

                $extension = explode('.', $user->foto);
                $extension = $extension[count($extension) - 1];
                $filename = $user->nip . '.' . $extension;

                // open and resize an image file
                $img = Image::make($file_server)->resize(128, 128);
                $img2 = Image::make($file_server);

                // make thumbnail
                Storage::disk('ftp_plnpusat')->put('app/foto-thumb/' . $filename, $img->response($extension));
                // copy ke foto
                Storage::disk('ftp_plnpusat')->put('app/foto/' . $filename, $img2->response($extension));
            } else {
                // jika tidak ada reset foto
//                $user->foto = '';
//                $user->save();
                echo 'ERROR : FILE NOT FOUND <br>';
            }

        }
    }

    public function volunteer()
    {
        return $this->hasMany('App\Volunteer', 'user_id', 'id');
    }

    public function checkVolunteer($evp_id)
    {
        $volunteer = Volunteer::where('user_id', $this->id)->where('evp_id', $evp_id)->first();
        $evp = EVP::find($evp_id);
//        dd($volunteer);

        // cek tanggal pendaftaran
        $now = Carbon::now();

        if ($evp->tgl_awal_registrasi->format('Ymd') <= $now->format('Ymd') && $evp->tgl_akhir_registrasi->format('Ymd') >= $now->format('Ymd')) {
            $tgl_daftar = true;
        } else {
            $tgl_daftar = false;
        }

//        dd($tgl_daftar);

        // cek unit
        $cc_user = Auth::user()->companyCode;
        $ba_user = Auth::user()->businessArea;
        if ($evp->companyCode()->where('id', $cc_user->id)->first() != null && $evp->businessArea()->where('id', $ba_user->id)->first() != null) {
            $unit = true;
        } else {
            $unit = false;
        }

//        dd($volunteer==null && $tgl_daftar && $unit);

        // jika belum daftar && tgl daftar && unit && tidak mendaftar pada program lain dengan waktu yang sama -> boleh daftar
//        if ($volunteer == null && $tgl_daftar && $unit && $this->checkVolunteerProgram($evp_id))
        if ($volunteer == null && $tgl_daftar && $unit)
            return true;
        else
            return false;
    }

    public function checkVolunteerProgram($evp_id)
    {
        $volunteer_list = Volunteer::where('user_id', $this->id)->get();
        $evp = EVP::find($evp_id);

        // cek apakah sedang mendaftar pada program lain pada waktu yang sama
        foreach ($volunteer_list as $volunteer) {
            if (($volunteer->evp->waktu_awal <= $evp->waktu_awal && $volunteer->evp->waktu_awal >= $evp->waktu_awal) ||
                ($volunteer->evp->waktu_awal <= $evp->waktu_akhir && $volunteer->evp->waktu_awal >= $evp->waktu_akhir)
            ) {
                return false;
            }
        }

        return true;

    }

    public function getMGRP()
    {
        if (isset( $this->pa0032->pa0001)) {
            return $this->pa0032->pa0001->hrp1513()->orderBy('endda', 'desc')->value('mgrp');
        }

        return null;
    }

    public function isStruktural()
    {
        $mgrp = $this->getMGRP();
        if ($mgrp == '04')
            return true;
        else
            return false;
    }

    public function getJumlahEVP()
    {
        return $this->volunteer()->count();
    }

    public function getJumlahCoC()
    {
        return $this->attendant()->count();
    }

    public function getJumlahMateriDibaca()
    {
        return $this->readMateri()->count();
    }

    public function getJumlahLeaderCoC()
    {
        $pernr = $this->strukturJabatan->pernr;
        $leader = RealisasiCoc::where('pernr_leader', $pernr)->get();
        return $leader->count();
    }

    public function getJumlahCoCTahun($tahun)
    {
        return $this->attendant()->whereYear('check_in', '=', $tahun)->count();
    }

    public function getJumlahMateriDibacaTahun($tahun)
    {
        return $this->readMateri()->whereYear('tanggal_jam', '=', $tahun)->count();
    }

    public function getJumlahLeaderCoCTahun($tahun)
    {
        if($this->strukturJabatan!=null){
            $pernr = $this->strukturJabatan->pernr;
            $leader = RealisasiCoc::where('pernr_leader', $pernr)->whereYear('realisasi', '=', $tahun)->get();
            return $leader->count();
        }
        else{
            return null;
        }
    }

    public function getStrukjabAtasan()
    {

        // get orgeh pegawai
        $orgeh_pegawai = $this->strukturJabatan->orgeh;
        $plans_pegawai = $this->strukturJabatan->plans;
//        $orgeh_pegawai = '10074408';
//        $plans_pegawai = '30207817';


        // cek fungsional (MGRP=05)
        $hrp1513 = HRP1513::where('objid', $plans_pegawai)->first();

//        dd($hrp1513->mgrp);
        // jika fungsional
        if ($hrp1513->mgrp == '05') {

            // get objid relat 12
            $strukpos_atasan = StrukturPosisi::where('sobid', $orgeh_pegawai)->where('relat', '12')->first();
            $plans_atasan = $strukpos_atasan->objid;
//            dd($plans_atasan);

            // get strukjab atasan
            $strukjab_atasan = StrukturJabatan::where('plans', $plans_atasan)->first();
//            dd($plans_atasan);

            // jika tidak ditemukan pada strukjab, cari di ZPDELEGATION (PLT)
            if ($strukjab_atasan == null) {
                $delegation = ZPDelegation::where('position_1', $plans_atasan)
                    ->where('endda', '>=', Carbon::now()->format('Ymd'))
                    ->where('begda', '<=', Carbon::now()->format('Ymd'))
                    ->orderBy('endda', 'desc')->first();
//                dd($delegation);
                if ($delegation == null)
                    return redirect('evp/program')->with('error', 'Atasan belum definitif dan tidak ada data delegasi. Silakan hubungi Administrator.');
                $strukjab_atasan = StrukturJabatan::where('plans', $delegation->position_2)->first();
//                dd($strukjab_atasan);
            }
        } // jika struktural
        else {
            $strukorg_atasan = StrukturOrganisasi::where('objid', $orgeh_pegawai)->first();
//            dd($strukorg_atasan);

            // get objid relat 12
            $strukpos_atasan = StrukturPosisi::where('sobid', $strukorg_atasan->sobid)->where('relat', '12')->first();
            $plans_atasan = $strukpos_atasan->objid;
//            dd($strukpos_atasan);

            // get strukjab atasan
            $strukjab_atasan = StrukturJabatan::where('plans', $plans_atasan)->first();
//            dd($strukjab_atasan);

            // jika tidak ditemukan pada strukjab, cari di ZPDELEGATION (PLT)
            if ($strukjab_atasan == null) {
                $delegation = ZPDelegation::where('position_1', $plans_atasan)
                    ->where('endda', '>=', Carbon::now()->format('Ymd'))
                    ->where('begda', '<=', Carbon::now()->format('Ymd'))
                    ->orderBy('endda', 'desc')->first();
//                dd($delegation);
                if ($delegation == null)
                    return redirect('evp/program')->with('error', 'Atasan belum definitif dan tidak ada data delegasi. Silakan hubungi Administrator.');
                $strukjab_atasan = StrukturJabatan::where('plans', $delegation->position_2)->first();
            }
        }

//        dd($strukjab_atasan);
//        if ($strukjab_atasan == null)
//            return redirect('evp/program')->with('error', 'Gagal mengambil data atasan. Silakan hubungi Administrator.');

        return $strukjab_atasan;

    }

    public function getStrukjabGM()
    {
        // get orgeh pegawai
        $orgeh_pegawai = $this->strukturJabatan->orgeh;
//        $plans_pegawai = $this->strukturJabatan->plans;
//        $orgeh_pegawai = '16200598';
//        $plans_pegawai = '37305668';


        $orgeh = $orgeh_pegawai;
        do {
//        dd($hrp1513);
            $strukorg_atasan = StrukturOrganisasi::where('objid', $orgeh)->first();
//            dd($strukorg_atasan);

            // get objid relat 12
            $strukpos_atasan = StrukturPosisi::where('sobid', $strukorg_atasan->sobid)->where('relat', '12')->first();
            $plans_atasan = $strukpos_atasan->objid;
//            dd($strukpos_atasan);

            // cek apakah GM / KDIV
            $hrp1513 = HRP1513::where('objid', $plans_atasan)
                ->where('mgrp', '04')
                ->where('sgrp', '01')
                ->first();
//        dd($hrp1513);
            $orgeh = $strukorg_atasan->sobid;

        } while ($hrp1513 == null);

//        dd($hrp1513);

        $plans_gm = $hrp1513->objid;

        // get strukjab GM
        $strukjab_gm = StrukturJabatan::where('plans', $plans_gm)->first();
//        dd($strukjab_gm);

        // jika tidak ditemukan pada strukjab, cari di ZPDELEGATION (PLT)
        if ($strukjab_gm == null) {
            $delegation = ZPDelegation::where('position_1', $plans_gm)
                ->where('endda', '>=', Carbon::now()->format('Ymd'))
                ->where('begda', '<=', Carbon::now()->format('Ymd'))
                ->orderBy('endda', 'desc')->first();
//                dd($delegation);
            if ($delegation == null)
                return redirect('evp/program')->with('error', 'GM/KDIV belum definitif dan tidak ada data delegasi. Silakan hubungi Administrator.');
            $strukjab_gm = StrukturJabatan::where('plans', $delegation->position_2)->first();
        }

//        dd($strukjab_gm);

        return $strukjab_gm;

	}

	public function atasans()
	{
		return $this->hasMany(LiquidPeserta::class, 'bawahan_id');
    }

    public function dashboardMenu()
    {
        if(session('menu_liquid')!=null){
            $result = session('menu_liquid');
        }
        else{
            $result = [];

            if ($this->can(LiquidPermission::ACCESS_DASHBOARD)) {
                array_push($result, LiquidMenuEnum::ADMIN);
            }

            $hasBawahan = app(LiquidService::class)->hasLiquidBawahan();
            $hasAtasan = app(LiquidService::class)->hasLiquidAtasan();

            if($hasBawahan > 0){
                array_push($result, LiquidMenuEnum::ATASAN);
            }
            if($hasAtasan > 0){
                array_push($result, LiquidMenuEnum::BAWAHAN);
            }

            session(['menu_liquid'=>$result]);
        }

        return $result;
    }

    public function isAtasanLiquid(){
        $hasBawahan = app(LiquidService::class)->hasLiquidBawahan();
        if($hasBawahan > 0){
            return true;
        }

        return false;
    }

    public function isBawahanLiquid(){
        $hasAtasan = app(LiquidService::class)->hasLiquidAtasan();
        if($hasAtasan > 0){
            return true;
        }

        return false;
    }

    public function getKodeUnit()
    {
        if (isset($this->pa0032->pa0001)) {
            return $this->pa0032->pa0001->gsber;
        }

        return null;
	}

    public function pesertaAssessment()
    {
        return $this->hasMany('App\PesertaAssessment','nip_pegawai','nip');
    }

    public function verifikatorAssessment()
    {
        return $this->hasMany('App\PesertaAssessment','nip_verifikator','nip');
    }
    
    public function unitKerjas()
    {
        return $this->hasMany(UnitKerja::class);
    }

    public function activityLogBooks()
    {
        return $this->hasMany(ActivityLogBook::class, 'created_by');
    }

    public function validasiCV(){
        return $this->hasMany(ValidasiCV::class,'nip','nip');
    }
}
