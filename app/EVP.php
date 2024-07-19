<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EVP extends Model
{
    protected $table = 'm_evp';
    protected $dates = ['waktu_awal', 'waktu_akhir', 'tgl_awal_registrasi', 'tgl_akhir_registrasi', 'tgl_pengumuman', 'tgl_jam_briefing'];
    
    public function volunteers(){
        return $this->hasMany('App\Volunteer', 'evp_id', 'id');
    }

    public function rundownEVP(){
        return $this->hasMany('App\RundownEVP', 'evp_id', 'id');
    }

    public function jenisEVP(){
        return $this->belongsTo('App\JenisEVP', 'jenis_evp_id', 'id');
    }

    public function jenisWaktuEVP(){
        return $this->belongsTo('App\JenisWaktuEVP', 'jenis_waktu_id', 'id');
    }

    public function setJenisEvpIdAttribute($attrValue){
        $this->attributes['jenis_evp_id'] = (string) $attrValue;
    }

    public function setJenisWaktuIdAttribute($attrValue){
        $this->attributes['jenis_waktu_id'] = (string) $attrValue;
    }

    public function businessArea(){
        return $this->belongsToMany('App\BusinessArea','business_area_evp','evp_id','business_area');
    }

    public function companyCode(){
        return $this->belongsToMany('App\CompanyCode','company_code_evp','evp_id','company_code');
    }
}
