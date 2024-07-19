<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class
BusinessArea extends Model
{
    protected $table = 'm_business_area';

    public function companyCode(){
        return $this->belongsTo('App\CompanyCode', 'company_code', 'company_code');
    }

    public function users(){
        return $this->hasMany('App\User','business_area','business_area');
    }

    public function wilayah(){
        return $this->hasMany('App\Wilayah','business_area','business_area');
    }

    public function hrp1008(){
        return $this->hasMany('App\Hrp1008', 'BUKRS', 'company_code');
    }

    public function realisasi(){
        return $this->hasMany('App\RealisasiCoc', 'business_area', 'business_area');
    }

    public function cluster(){
        return $this->hasOne('App\ClusterUnit', 'business_area', 'business_area');
    }

    public function problem(){
        return $this->hasMany('App\Problem','bussiness_area', 'bussiness_area');
    }

    public function evp(){
        return $this->belongsToMany('App\EVP','business_area_evp','evp_id','business_area');
    }

    public function getSumCocRangeDate($tgl_awal, $tgl_akhir, $status){
        $coc = Coc::where('business_area',$this->business_area)
            ->whereDate('tanggal_jam','>=',$tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam','<=',$tgl_akhir->format('Y-m-d'))
            ->where('status',$status)
            ->count();
        return $coc;
    }

    public function getDescriptionOptionAttribute()
    {
        return $this->business_area . ' - ' . $this->description;
    }
}
