<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StrukturJabatanTmp extends Model
{
//    protected $table = 'm_strukjab_tmp';
    protected $table = 'm_struktur_jabatan_tmp';
    protected $primaryKey = 'pernr';
//    protected $table = 'm_struktur_jabatan_tmp';
//    const UPDATED_AT = null;
    protected $dates = ['updated_at'];
    protected $created_at = '';

    public function setUpdatedAt($value)
    {
        // Do nothing.
        ;
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }

    public function strukturPosisi()
    {
        return $this->belongsTo('App\StrukturPosisiSAP', 'plans', 'objid');
    }

    public function strukturOrganisasi()
    {
        return $this->belongsTo('App\StrukturOrganisasi', 'orgeh', 'objid');
    }

    public function readMateri()
    {
        return $this->hasMany('App\ReadMateri', 'pernr', 'pernr');
    }

    public function pa0001()
    {
        return $this->hasMany('App\PA0001', 'pernr', 'pernr');
    }

    public function getJenjangJabatan()
    {
        $jenjang = $this->pa0001()->where('mandt', '100')->where('endda', '99991231')->first();

        $jabatan = $jenjang->hrp1513()->where('mandt', '100')->where('endda', '99991231')->first();

//	dd($jabatan);
//        if($jabatan!=null) {

        $unit = LevelUnit::where('werks', $jenjang->werks)->where('btrtl', $jenjang->btrtl)->first();
//dd($unit);
        if ($jabatan->mgrp == '04' && $jabatan->sgrp == '01' && $unit->level == 1) {
//            $str_jabatan = 'GENERAL MANAGER';
            $jabatan_id = '1';
        } elseif ($jabatan->mgrp == '04' && $jabatan->sgrp == '02' && $unit->level == 1) {
//            $str_jabatan = 'MANAJER BIDANG';
            $jabatan_id = '2';
        } elseif ($jabatan->mgrp == '04' && $jabatan->sgrp == '03' && $unit->level == 1) {
//            $str_jabatan = 'DEPUTI MANAJER';
            $jabatan_id = '3';
        } elseif ($jabatan->mgrp == '04' && $jabatan->sgrp == '03' && $unit->level == 2) {
//            $str_jabatan = 'MANAJER AREA';
            $jabatan_id = '4';
        } elseif ($jabatan->mgrp == '04' && $jabatan->sgrp == '04' && $unit->level == 2) {
//            $str_jabatan = 'ASSISTAN MANAJER';
            $jabatan_id = '5';
        } elseif ($jabatan->mgrp == '04' && $jabatan->sgrp == '04' && $unit->level == 3) {
//            $str_jabatan = 'MANAJER AREA';
            $jabatan_id = '6';
        } //        }
        else {
            $str_jabatan = 'FUNGSIONAL';
            $jabatan_id = '7';
        }
//dd($jabatan_id);
        $jenjab = JenjangJabatan::findOrFail($jabatan_id);
//dd($jenjab);
        return $jenjab;
    }

    public function getLevelUnit()
    {
        $jenjang = $this->pa0001()->where('mandt', '100')->where('endda', '99991231')->first();

        $unit = LevelUnit::where('werks', $jenjang->werks)->where('btrtl', $jenjang->btrtl)->first();

        return $unit->level;
    }
    
    public static function getJumlahPegawai(){
//        $jml = StrukturJabatan::where('pernr_at', '!=', '0')->count();
	$jml = PA0001::where('persg', '=', '1')->count();
//        dd($jml);
        return $jml;
    }

    public function updateDataFromSAP(){
        // get file from ftp

        // proses file

        // update file


    }

    public function usr21()
    {
        return $this->hasOne('App\Usr21', 'bname', 'pernr');
    }

    public static function updateDataESS()
    {
//        $contents = Storage::disk('ftp_sap')->get('STRUKJAB_TBL_20180308030758.TXT');
        $directory = 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE';
//        $file = 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE/STRUKJAB_TBL_20180308030758.TXT';
        $contents = Storage::disk('ftp_sap')->files($directory);
//        $contents = Storage::disk('ftp_sap')->lastModified($file);
//        $contents = Storage::disk('ftp_sap')->exists($file);
//        $contents = Storage::disk('ftp_sap')->get($file);

        $file_strukjab = '';
        $file_strukpos = '';
        foreach ($contents as $file) {
            if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE/STRUKJAB_TBL_' . date('Ymd'))) {
                $file_strukjab = $file;
//                dd($file_strukjab);
            }
            if (starts_with($file, 'IFPR1100/OUTBOUND/PROMISE/ARCHIVE/STRUKPOS_TBL_' . date('Ymd'))) {
                $file_strukpos = $file;
            }
        }

//        STRUKJAB
//        NIP	PERNR	CNAME	PLANS	ORGEH	WERKS	BTRTL	MGRP	SGRP	SPEBE	KOSTL	PERNR_AT	PLANS_AT

        if (Storage::disk('ftp_sap')->exists($file_strukjab)) {
            $content_strukjab = Storage::disk('ftp_sap')->get($file_strukjab);
            $convert = nl2br($content_strukjab);
            $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
            foreach ($arr_content as $line) {
//                echo $line . '<br>';
                $arr_obj = explode('|', $line);
                if (count($arr_obj) == 12) {
                    $pernr = trim($arr_obj[0]);
                    $cname = trim($arr_obj[1]);
                    $plans = trim($arr_obj[2]);
                    $orgeh = trim($arr_obj[3]);
                    $werks = trim($arr_obj[4]);
                    $btrtl = trim($arr_obj[5]);
                    $mgrp = trim($arr_obj[6]);
                    $sgrp = trim($arr_obj[7]);
                    $spebe = trim($arr_obj[8]);
                    $kostl = trim($arr_obj[9]);
                    $pernr_at = trim($arr_obj[10]);
                    $plans_at = trim($arr_obj[11]);

                    // update data
                    $strukjab = StrukturJabatanTmp::where('pernr', $pernr)->first();
                    if ($strukjab == null) {
                        $strukjab = new StrukturJabatanTmp();
                        echo 'New -> ';
                        $pa0032 = PA0032::where('pernr', $pernr)->first();
                        $strukjab->nip = strtoupper(@$pa0032->nip);
                        $strukjab->pernr = $pernr;
                    }
                    $strukjab->cname = strtoupper($cname);
                    $strukjab->plans = $plans;
                    $strukjab->orgeh = $orgeh;
                    $strukjab->werks = $werks;
                    $strukjab->btrtl = $btrtl;
                    $strukjab->mgrp = $mgrp;
                    $strukjab->sgrp = $sgrp;
                    $strukjab->spebe = $spebe;
                    $strukjab->kostl = $kostl;
                    $strukjab->pernr_at = $pernr_at;
                    $strukjab->plans_at = $plans_at;
                    $strukjab->save();
                    echo $strukjab->nip.'|'.$strukjab->pernr.'|'.$strukjab->cname . '<br>';
                }
            }

        }


//        STRUKPOS
//        OBJID	STEXT	RELAT	SOBID	STXT2	STARTDATE	ENDDATE

        if (Storage::disk('ftp_sap')->exists($file_strukpos)) {
            $content_strukpos = Storage::disk('ftp_sap')->get($file_strukpos);
            $convert = nl2br($content_strukpos);
            $arr_content = explode('<br />', $convert);
//            echo count($arr_content).'<br />';
            foreach ($arr_content as $line) {
//                if(trim($line)!='') {
//                echo $line . '<br>';

                $arr_obj = explode('|', $line);
//                dd($arr_obj);

                if (count($arr_obj) == 11) {
                    $objid = trim($arr_obj[0]);
                    $stext = trim($arr_obj[2]);
                    $relat = trim($arr_obj[4]);
                    $sobid = trim($arr_obj[5]);
                    $stxt2 = trim($arr_obj[7]);

                    // update data
                    $strukpos = StrukturPosisiTmp::where('objid', $objid)->first();
//                    dd($strukjab);
                    if ($strukpos == null) {
                        $strukpos = new StrukturPosisiTmp();
                        echo 'New -> ';

                        $strukpos->objid = $objid;
                        $strukpos->stext = $stext;
                        $strukpos->relat = $relat;
                        $strukpos->sobid = $sobid;
                        $strukpos->stxt2 = $stxt2;
                        $strukpos->save();
                        echo $strukpos->objid . '|' . $strukpos->stext . '|' . $strukpos->sobid . '|' . $strukpos->stxt2 . '<br>';
//                    dd($strukjab);
//                    }
                    }
                }

            }
        }


//        dd($contents);

        return 'FINISH';
    }
}
