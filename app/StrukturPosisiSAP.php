<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StrukturPosisiSAP extends Model
{
    protected $table = 'm_strukpos_sap';
//    protected $table = 'm_struktur_posisi_tmp';

    public function strukturJabatan(){
        return $this->hasMany('App\StrukturJabatanSAP', 'plans', 'objid');
    }

    public function organisasi(){
        return $this->belongsTo('App\StrukturOrganisasi', 'sobid', 'objid');
    }

    public function jawabanPegawai(){
        return $this->hasMany('App\JawabanPegawai', 'plans', 'objid');
    }

    public function komitmenPegawai(){
        return $this->hasMany('App\JawabanPegawai', 'plans', 'objid');
    }

    public function setIdAttribute($attrValue){
        $this->attributes['id'] = (string) $attrValue;
    }

    public function setObjidAttribute($attrValue){
        $this->attributes['objid'] = (string) $attrValue;
    }

    public function setSobidAttribute($attrValue){
        $this->attributes['sobid'] = (string) $attrValue;
    }
}
