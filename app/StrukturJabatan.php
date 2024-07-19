<?php

namespace App;

use App\Models\Liquid\LiquidPeserta;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class StrukturJabatan extends Model
{
    protected $table = 'm_struktur_jabatan';
    protected $primaryKey = 'pernr';
    protected $keyType = 'string';
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
        return $this->belongsTo('App\User', 'nip', 'nip');
    }

    public function strukturPosisi()
    {
        return $this->belongsTo('App\StrukturPosisi', 'plans', 'objid');
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
        $jenjang = $this->pa0001()->where('mandt', '100')->orderBy('endda','desc')->take(1)->get()->first();
        $jabatan = $jenjang->hrp1513()->where('mandt', '100')->orderBy('endda','desc')->take(1)->get()->first();

        $unit = LevelUnit::where('werks', $jenjang->werks)->where('btrtl', $jenjang->btrtl)->first();
        if($unit == null || $unit->level==null || $jabatan==null){
            return null;
        }

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
//        $jenjang = $this->pa0001()->where('mandt', '100')->where('endda', '99991231')->first();
        $jenjang = $this->pa0001()->where('mandt', '100')->orderBy('endda','desc')->take(1)->get()->first();

        $unit = LevelUnit::where('werks', $jenjang->werks)->where('btrtl', $jenjang->btrtl)->first();

	if($unit == null){
		return null;
	}

        return $unit->level;
    }

    public static function getJumlahPegawai(){
//        $jml = StrukturJabatan::where('pernr_at', '!=', '0')->count();
        if( Cache::has('jml_pegawai') ) {
            return Cache::get('jml_pegawai');
        }
            $jml = PA0001::where('persg', '=', '1')->count();
            Cache::put( 'jml_pegawai', $jml, 1440 );
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

    public static function importFromTmp(){
        $hrp1008 = StrukturJabatan::orderBy('pernr', 'asc')->get(['pernr']);
        $flat = $hrp1008->pluck('pernr');

//        foreach($flat as $data){
//            echo $data.', ';
//        }
//
//        echo '<br>';

        $hrp1008_tmp = StrukturJabatanSAP::orderBy('pernr', 'asc')->get(['pernr']);
        $flat_tmp = $hrp1008_tmp->pluck('pernr');

//        foreach($flat_tmp as $data){
//            echo $data.', ';
//        }
//
//        echo '<br>';

        $diff = $flat_tmp->diff($flat);

//        dd($diff->count());

        foreach($diff as $data){
            echo $data.', ';
            $source = StrukturJabatanSAP::where('pernr',$data)->first();
            $target = new StrukturJabatan();

//            PERNR	NIP

            $target->pernr = $source->pernr;
            $target->nip = $source->nip;
            $target->cname = $source->cname;
            $target->plans = $source->plans;
            $target->orgeh = $source->orgeh;
            $target->werks = $source->werks;
            $target->btrtl = $source->btrtl;
            $target->mgrp = $source->mgrp;
            $target->sgrp = $source->sgrp;
            $target->spebe = $source->spebe;
            $target->kostl = $source->kostl;
            $target->pernr_at = $source->pernr_at;
            $target->plans_at = $source->plans_at;
            $target->save();

//            dd($target);
        }

//        dd($diff);

        echo 'FINISH';

    }

    public function excludeInterface(){
        return $this->hasOne('App\EksepsiInterface', 'pernr', 'pernr');
    }

    public function getDefinitive(){
        $delegation = ZPDelegation::where('position_2', $this->plans)
            ->where('endda', '>=', Carbon::now()->format('Ymd'))
            ->where('begda', '<=', Carbon::now()->format('Ymd'))
            ->orderBy('endda', 'desc')->first();

        if($delegation!=null)
            return $delegation->position_1;
        else
            return $this->plans;
	}

	public function atasans()
	{
		return $this->hasMany(LiquidPeserta::class, 'bawahan_id', 'pernr');
	}

    public function bawahans()
	{
		return $this->hasMany(LiquidPeserta::class, 'atasan_id', 'pernr');
	}
}
