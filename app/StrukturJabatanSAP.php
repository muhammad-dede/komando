<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StrukturJabatanSAP extends Model
{
    protected $table = 'm_strukjab_sap';
//    protected $table = 'm_struktur_jabatan_tmp';

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
}
