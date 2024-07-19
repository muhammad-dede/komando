<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TargetCoc extends Model
{
    protected $table = 'target_coc';

    public function jenjangJabatan()
    {
        return $this->belongsTo('App\JenjangJabatan', 'jenjang_id', 'id');
    }

    public static function getTargetCluster($company_code, $bulan, $tahun){

        if($bulan>=1 && $bulan<=3) $tw = 'tw1';
        elseif($bulan>=4 && $bulan<=6) $tw = 'tw2';
        elseif($bulan>=7 && $bulan<=9) $tw = 'tw3';
        elseif($bulan>=10 && $bulan<=12) $tw = 'tw4';
        $cc = CompanyCode::where('company_code', $company_code)->first();
        $targets = TargetCoc::where('tahun', $tahun)
                    ->where('cluster', $cc->cluster)
                    ->orderBy('jenjang_id', 'asc')->get();
//        dd($targets);
        $arr_target = [];
        foreach ($targets as $target) {
            $arr_target[$target->jenjang_id] = $target->getAttribute($tw);
        }
        
        return $arr_target;
    }

    public static function getTargetClusterJenjang($company_code, $bulan, $tahun, $jenjang_id){

        if($bulan>=1 && $bulan<=3) $tw = 'tw1';
        elseif($bulan>=4 && $bulan<=6) $tw = 'tw2';
        elseif($bulan>=7 && $bulan<=9) $tw = 'tw3';
        elseif($bulan>=10 && $bulan<=12) $tw = 'tw4';
        $cc = CompanyCode::where('company_code', $company_code)->first();
        $target = TargetCoc::where('tahun', $tahun)
            ->where('cluster', $cc->cluster)
            ->where('jenjang_id', $jenjang_id)
            ->first();
//        dd($target->getAttribute($tw));
//        $arr_target = [];
//        foreach ($targets as $target) {
//            $arr_target[$target->jenjang_id] = $target->getAttribute($tw);
//        }

        return $target->getAttribute($tw);
    }
}
