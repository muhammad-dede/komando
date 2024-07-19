<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyCode extends Model
{
    protected $table = 'm_company_code';

    public function businessArea(){
        return $this->hasMany('App\BusinessArea', 'company_code', 'company_code');
    }

    public function users(){
        return $this->hasMany('App\User','company_code','company_code');
    }

    public function wilayah(){
        return $this->hasOne('App\Wilayah','company_code','company_code');
    }

    public function hrp1008(){
        return $this->hasMany('App\Hrp1008', 'BUKRS', 'company_code');
    }

    public function realisasi(){
        return $this->hasMany('App\RealisasiCoc', 'company_code', 'company_code');
    }

    public function levelUnit()
    {
        return $this->hasMany('App\LevelUnit', 'company_code', 'company_code');
    }

    public function problem(){
        return $this->hasMany('App\Problem','company_code', 'company_code');
    }

    public function evp(){
        return $this->belongsToMany('App\EVP','company_code_evp','evp_id','company_code');
    }

    public function getSumCocRangeDate($tgl_awal, $tgl_akhir, $status)
    {
        $coc = Coc::where('company_code', $this->company_code)
            ->whereDate('tanggal_jam', '>=', $tgl_awal->format('Y-m-d'))
            ->whereDate('tanggal_jam', '<=', $tgl_akhir->format('Y-m-d'))
            ->where('status', $status)
            ->count();

        return $coc;
    }

    public function getDescriptionOptionAttribute()
    {
        return $this->company_code . ' - ' . $this->description;
    }
}
