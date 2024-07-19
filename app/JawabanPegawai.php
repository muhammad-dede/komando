<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JawabanPegawai extends Model
{
    protected $table = 'jawaban_pegawai';

    public function setUserIdAttribute($attrValue){
        $this->attributes['user_id'] = (string) $attrValue;
    }

    public function setOrgehAttribute($attrValue){
        $this->attributes['orgeh'] = (string) $attrValue;
    }

    public function setPlansAttribute($attrValue){
        $this->attributes['plans'] = (string) $attrValue;
    }

    public function setPedomanPerilakuIdAttribute($attrValue){
        $this->attributes['pedoman_perilaku_id'] = (string) $attrValue;
    }

    public function setPertanyaanIdAttribute($attrValue){
        $this->attributes['pertanyaan_id'] = (string) $attrValue;
    }

    public function setJawabanIdAttribute($attrValue){
        $this->attributes['jawaban_id'] = (string) $attrValue;
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function organisasi(){
        return $this->belongsTo('App\StrukturOrganisasi', 'orgeh', 'objid');
    }

    public function posisi(){
        return $this->belongsTo('App\StrukturPosisi', 'plans', 'objid');
    }

    public function pedomanPerilaku(){
        return $this->belongsTo('App\PedomanPerilaku', 'pedoman_perilaku_id', 'id');
    }

    public function pertanyaan(){
        return $this->belongsTo('App\Pertanyaan', 'pertanyaan_id', 'id');
    }

    public function jawaban(){
        return $this->belongsTo('App\Jawaban', 'jawaban_id', 'id');
    }
}
