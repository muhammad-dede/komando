<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StrukturPosisiRev extends Model
{
    protected $table = 'm_struktur_posisi_tmp';
//    protected $table = 'm_struktur_posisi_tmp';

    public function strukturJabatan(){
        return $this->hasMany('App\StrukturJabatan', 'plans', 'objid');
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

    public static function importFromTmp(){
        $hrp1008 = StrukturPosisi::orderBy('objid', 'asc')->get(['objid']);
        $flat = $hrp1008->pluck('objid');

//        foreach($flat as $data){
//            echo $data.', ';
//        }
//
//        echo '<br>';

        $hrp1008_tmp = StrukturPosisiSAP::orderBy('objid', 'asc')->get(['objid']);
        $flat_tmp = $hrp1008_tmp->pluck('objid');

//        foreach($flat_tmp as $data){
//            echo $data.', ';
//        }
//
//        echo '<br>';

        $diff = $flat_tmp->diff($flat);

//        dd($diff->count());

        foreach($diff as $data){
            echo $data.', ';
            $source = StrukturPosisiSAP::where('objid',$data)->first();
            $target = new StrukturPosisi();

//            PERNR	NIP

            $target->objid = $source->objid;
            $target->stext = $source->stext;
            $target->relat = $source->relat;
            $target->sobid = $source->sobid;
            $target->stxt2 = $source->stxt2;
            $target->save();

//            dd($target);
        }

//        dd($diff);

        echo 'FINISH';

    }
}
