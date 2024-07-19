<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenjangJabatan extends Model
{
    //
    protected $table = 'm_jenjang';

    public function realisasi()
    {
        return $this->hasMany('App\JenjangJabatan', 'jenjang_id', 'id');
    }
    
    public function targetCoc()
    {
        return $this->hasMany('App\TargetCoc', 'jenjang_id', 'id');
    }

    public function targetCocTahun($tahun)
    {
        return $this->targetCoc()->where('tahun',$tahun)->first();
    }
    
    public static function getJumlahPejabat($company_code, $jabatan_id){
        
        $jml = 0;
        
//        $cc = CompanyCode::where('company_code', $company_code)->first();
//        $level_unit = LevelUnit::where('company_code', $company_code)->where('level', $level)->get();
        $jabatan = JenjangJabatan::findOrFail($jabatan_id);

        $mgrp = '04';
        if($jabatan_id==1 && $jabatan->level==1) $sgrp = '01';          // GM
        elseif ($jabatan_id==2 && $jabatan->level==1) $sgrp = '02';     // MB
        elseif ($jabatan_id==3 && $jabatan->level==1) $sgrp = '03';     // DM
        elseif ($jabatan_id==4 && $jabatan->level==2) $sgrp = '03';     // Man Area
        elseif ($jabatan_id==5 && $jabatan->level==2) $sgrp = '04';     // Asman
        elseif ($jabatan_id==6 && $jabatan->level==3) $sgrp = '04';     // Man Rayon
//        foreach ($level_unit as $unit){
//            $pegs = PA0001::where('mandt', '100')
//                ->where('werks', $unit->werks)
//                ->where('btrtl', $unit->btrtl)
//                ->where('endda','99991231')
//                ->get();
//
//            foreach ($pegs as $peg){
//                $jabatan = $peg->jabatan->getJenjangJabatan();
//                dd($jabatan);
//                if($jabatan->id == $jabatan_id) $jml++;
////                echo $jabatan->jenjang_jabatan."<br>";
//            }
//        }

        $btrtls = LevelUnit::where('company_code', $company_code)
            ->where('level', $jabatan->level)
            ->get(['btrtl'])
            ->toArray();

        //dd($btrtls);

        $plans = PA0001::where('mandt', '100')
//            ->where('endda', '99991231')
            ->where('persg', '1')
            ->where('bukrs', $company_code)
//            ->where('plans', '!=', '99999999')
//            ->where('plans', '!=', '00000000')
            ->whereIn('btrtl', $btrtls)
//            ->get();
            ->get(['plans'])->toArray();

//        dd($plans);

        $hrp = HRP1513::where('mandt', '100')
            ->where('endda', '99991231')
            ->where('mgrp',$mgrp)
            ->where('sgrp', $sgrp)
            ->whereIn('objid', $plans)
            ->get();

//        dd($hrp);
        
        return $hrp->count();
    }

    public static function getJumlahPejabatOrgeh($company_code, $jabatan_id){

        $jml = 0;

//        $cc = CompanyCode::where('company_code', $company_code)->first();
//        $level_unit = LevelUnit::where('company_code', $company_code)->where('level', $level)->get();
        $jabatan = JenjangJabatan::findOrFail($jabatan_id);

        $mgrp = '04';
        if($jabatan_id==1 && $jabatan->level==1) $sgrp = '01';          // GM
        elseif ($jabatan_id==2 && $jabatan->level==1) $sgrp = '02';     // MB
        elseif ($jabatan_id==3 && $jabatan->level==1) $sgrp = '03';     // DM
        elseif ($jabatan_id==4 && $jabatan->level==2) $sgrp = '03';     // Man Area
        elseif ($jabatan_id==5 && $jabatan->level==2) $sgrp = '04';     // Asman
        elseif ($jabatan_id==6 && $jabatan->level==3) $sgrp = '04';     // Man Rayon
//        foreach ($level_unit as $unit){
//            $pegs = PA0001::where('mandt', '100')
//                ->where('werks', $unit->werks)
//                ->where('btrtl', $unit->btrtl)
//                ->where('endda','99991231')
//                ->get();
//
//            foreach ($pegs as $peg){
//                $jabatan = $peg->jabatan->getJenjangJabatan();
//                dd($jabatan);
//                if($jabatan->id == $jabatan_id) $jml++;
////                echo $jabatan->jenjang_jabatan."<br>";
//            }
//        }

        $btrtls = LevelUnit::where('company_code', $company_code)
            ->where('level', $jabatan->level)
            ->get(['btrtl'])
            ->toArray();

        dd($btrtls);

        $plans = PA0001::where('mandt', '100')
//            ->where('endda', '99991231')
            ->where('persg', '1')
            ->where('bukrs', $company_code)
//            ->where('plans', '!=', '99999999')
//            ->where('plans', '!=', '00000000')
            ->whereIn('btrtl', $btrtls)
//            ->get();
            ->get(['plans'])->toArray();

//        dd($plans);

        $hrp = HRP1513::where('mandt', '100')
            ->where('endda', '99991231')
            ->where('mgrp',$mgrp)
            ->where('sgrp', $sgrp)
            ->whereIn('objid', $plans)
            ->get();

//        dd($hrp);

        return $hrp->count();
    }

    public static function getListPejabat($company_code, $jabatan_id){

        $jml = 0;

//        $cc = CompanyCode::where('company_code', $company_code)->first();
//        $level_unit = LevelUnit::where('company_code', $company_code)->where('level', $level)->get();
        $jabatan = JenjangJabatan::findOrFail($jabatan_id);

        $mgrp = '04';
        if($jabatan_id==1 && $jabatan->level==1) $sgrp = '01';          // GM
        elseif ($jabatan_id==2 && $jabatan->level==1) $sgrp = '02';     // MB
        elseif ($jabatan_id==3 && $jabatan->level==1) $sgrp = '03';     // DM
        elseif ($jabatan_id==4 && $jabatan->level==2) $sgrp = '03';     // Man Area
        elseif ($jabatan_id==5 && $jabatan->level==2) $sgrp = '04';     // Asman
        elseif ($jabatan_id==6 && $jabatan->level==3) $sgrp = '04';     // Man Rayon
//        foreach ($level_unit as $unit){
//            $pegs = PA0001::where('mandt', '100')
//                ->where('werks', $unit->werks)
//                ->where('btrtl', $unit->btrtl)
//                ->where('endda','99991231')
//                ->get();
//
//            foreach ($pegs as $peg){
//                $jabatan = $peg->jabatan->getJenjangJabatan();
//                dd($jabatan);
//                if($jabatan->id == $jabatan_id) $jml++;
////                echo $jabatan->jenjang_jabatan."<br>";
//            }
//        }

        $btrtls = LevelUnit::where('company_code', $company_code)
            ->where('level', $jabatan->level)
            ->get(['btrtl'])
            ->toArray();

//        dd($btrtls);

        $plans = PA0001::where('mandt', '100')
//            ->where('endda', '99991231')
            ->where('persg', '1')
            ->where('bukrs', $company_code)
//            ->where('plans', '!=', '99999999')
//            ->where('plans', '!=', '00000000')
            ->whereIn('btrtl', $btrtls)
//            ->get();
            ->get(['plans'])->toArray();

//        dd($plans);

        $objid = HRP1513::where('mandt', '100')
            ->where('endda', '99991231')
            ->where('mgrp',$mgrp)
            ->where('sgrp', $sgrp)
            ->whereIn('objid', $plans)
            ->get(['objid'])->toArray();

        $pejabat = PA0001::where('mandt', '100')
//            ->where('endda', '99991231')
            ->where('persg', '1')
            ->where('bukrs', $company_code)
//            ->where('plans', '!=', '99999999')
//            ->where('plans', '!=', '00000000')
            ->whereIn('btrtl', $btrtls)
            ->whereIn('plans', $objid)
//            ->get();
            ->get();

//        dd($pejabat);

        return $pejabat;
    }
}
