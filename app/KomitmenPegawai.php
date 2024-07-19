<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Yajra\Oci8\Eloquent\OracleEloquent as Model;
use Illuminate\Support\Facades\Cache;

class KomitmenPegawai extends Model
{
    protected $table = 'komitmen_pegawai';

    public function setUserIdAttribute($attrValue){
        $this->attributes['user_id'] = (string) $attrValue;
    }

    public function setOrgehAttribute($attrValue){
        $this->attributes['orgeh'] = (string) $attrValue;
    }

    public function setPlansAttribute($attrValue){
        $this->attributes['plans'] = (string) $attrValue;
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

    public static function getKomitmenPegawai(){
        if( Cache::has('jml_komit_pegawai') ) {
            return Cache::get('jml_komit_pegawai');
        }
//        $jml = KomitmenPegawai::where('tahun','=',date('Y'))->get()->count();
        $jml = KomitmenPegawai::where('tahun','=',date('Y'))->get(['user_id'])->pluck('user_id')->unique()->count();
        Cache::put( 'jml_komit_pegawai', $jml, 1440 );

//        dd($jml);
        return $jml;
    }
}
