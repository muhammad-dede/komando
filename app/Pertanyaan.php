<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    protected $table = 'pertanyaan';

    public function setPedomanPerilakuIdAttribute($attrValue){
        $this->attributes['pedoman_perilaku_id'] = (string) $attrValue;
    }

    public function setJenisAttribute($attrValue){
        $this->attributes['jenis'] = (string) $attrValue;
    }

    public function pedomanPerilaku(){
//        return $this->belongsTo('App\PedomanPerilaku', 'pedoman_perilaku_id', 'id');
        return $this->belongsTo('App\PLNTerbaik', 'pedoman_perilaku_id', 'id');
    }

    public function jawaban(){
        return $this->hasMany('App\Jawaban', 'pertanyaan_id', 'id');
    }

    public function jawabanPegawai(){
        return $this->hasMany('App\JawabanPegawai', 'pertanyaan_id', 'id');
    }

    public function getJawabanBenar(){
        return $this->jawaban()->where('benar','1')->first();
    }
}
