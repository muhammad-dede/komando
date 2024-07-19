<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealisasiCoc extends Model
{
    //
    protected $table = 'realisasi_coc';
    protected $dates = ['realisasi'];

    public function coc()
    {
        return $this->belongsTo('App\Coc', 'coc_id', 'id');
    }

    public function jenjangJabatan()
    {
        return $this->belongsTo('App\JenjangJabatan', 'jenjang_id', 'id');
    }

    public function leader()
    {
        // return $this->belongsTo('App\StrukturJabatan', 'pernr_leader', 'pernr');
        return $this->belongsTo('App\User', 'nip_leader', 'nip');
    }

    public function companyCode()
    {
        return $this->belongsTo('App\CompanyCode', 'company_code', 'company_code');
    }

    public function businessArea()
    {
        return $this->belongsTo('App\BusinessArea', 'business_area', 'business_area');
    }

    public static function getRealisasiUnit($jenjang_id, $company_code, $tgl_awal, $tgl_akhir){
        $realisasi = RealisasiCoc::where('company_code', $company_code)
                    ->where('jenjang_id', $jenjang_id)
                    ->whereDate('realisasi', '>=', $tgl_awal->format('Y-m-d'))
                    ->whereDate('realisasi', '<=', $tgl_akhir->format('Y-m-d'))
                    ->get();
//        dd($realisasi->count());
        return $realisasi->count();
    }

    public static function getRealisasiPejabat($pernr, $business_area, $tgl_awal, $tgl_akhir){
        $realisasi = RealisasiCoc::where('pernr_leader', $pernr)
            ->where('business_area', $business_area)
            ->whereDate('realisasi', '>=', $tgl_awal->format('Y-m-d'))
            ->whereDate('realisasi', '<=', $tgl_akhir->format('Y-m-d'))
            ->get();

        return $realisasi->count();
    }

    public static function getRealisasiJabatan($plans, $business_area, $tgl_awal, $tgl_akhir){
        // cek PLT atau bukan

        // cari realiasasi berdasarkan posisi
        $realisasi = RealisasiCoc::where('plans', $plans)
            ->where('business_area', $business_area)
            ->whereDate('realisasi', '>=', $tgl_awal->format('Y-m-d'))
            ->whereDate('realisasi', '<=', $tgl_akhir->format('Y-m-d'))
            ->get();

        return $realisasi->count();
    }

    public function setJenjangIdAttribute($attrValue){
        $this->attributes['jenjang_id'] = (string) $attrValue;
    }

    public static function initialOrgehRealisasi(){
        $realisasi = RealisasiCoc::whereNull('orgeh')
                    ->whereNull('plans')
                    ->whereNull('delegation')
                    ->get();

        foreach($realisasi as $real){
            $pernr = $real->pernr_leader;
            $pa0001 = PA0001::where('pernr', $pernr)->first();
            if($pa0001!=null) {
                $real->orgeh = $pa0001->orgeh;
                $real->plans = @$pa0001->getDefinitive();
                $real->delegation = $pa0001->plans;
//            dd($real);
                $real->save();
                echo $real->pernr_leader . '|' . $real->orgeh . '|' . $real->plans . '<br>';
            }
        }

        return 'FINISH';

    }

    public static function updateDelegationRealisasi(){
        $realisasi = RealisasiCoc::all();

        foreach($realisasi as $real){
            $pernr = $real->pernr_leader;
            $pa0001 = PA0001::where('pernr', $pernr)->first();
            if($pa0001!=null) {
//                $real->orgeh = $pa0001->orgeh;
                $real->plans = @$pa0001->getDefinitive();
                $real->delegation = $pa0001->plans;
//            dd($real);
                $real->save();
                echo $real->pernr_leader . '|' . $real->orgeh . '|' . $real->plans . '<br>';
            }
        }

        return 'FINISH';

    }

    public static function updateNIPLeader(){
        $realisasi = RealisasiCoc::whereNull('nip_leader')->orderBy('id','desc')->take(100000)->get();
        // dd($coc);
        foreach ($realisasi as $r){
            $pernr_leader = $r->pernr_leader;
            $leader = StrukturJabatan::where('pernr',$pernr_leader)->first();
            if($leader!=null){
                $r->nip_leader = $leader->nip;
                // $r->nip_pemateri = $leader->nip;
                $r->save();

                echo $r->id." - ".$r->nip_leader." - ".$r->realisasi."\n";
            }
        }
    }
}
