<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{

    protected $table = 'jawaban';

    public function setPertanyaanIdAttribute($attrValue){
        $this->attributes['pertanyaan_id'] = (string) $attrValue;
    }

    public function setIndexAttribute($attrValue){
        $this->attributes['index'] = (string) $attrValue;
    }

    public function setBenarAttribute($attrValue){
        $this->attributes['benar'] = (string) $attrValue;
    }

    public function pertanyaan(){
        return $this->belongsTo('App\Pertanyaan', 'pertanyaan_id', 'id');
    }

    public function jawabanPegawai(){
        return $this->hasMany('App\JawabanPegawai', 'jawaban_id', 'id');
    }
}
