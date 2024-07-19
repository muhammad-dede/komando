<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Autocomplete extends Model
{
    protected $table = 'autocomplete_log';
    protected $dates = ['tanggal_coc'];

    public function coc(){
        return $this->belongsTo('App\Coc', 'coc_id', 'id');
    }
    public function realisasi(){
        return $this->belongsTo('App\RealisasiCoc', 'realisasi_coc_id', 'id');
    }

    public static function log($type, $activity, $coc_id, $tanggal, $realisasi_coc_id){
        $log                    = new Autocomplete();
        $log->coc_id            = $coc_id;
        $log->tanggal_coc           = $tanggal;
        $log->realisasi_coc_id  = $realisasi_coc_id;
        $log->type              = $type; // success, danger, info, warning
        $log->text              = $activity;
        $log->save();
    }

    public function setCocIdAttribute($attrValue){
        $this->attributes['coc_id'] = (string) $attrValue;
    }

    public function setRealisasiCocIdAttribute($attrValue){
        $this->attributes['realisasi_coc_id'] = (string) $attrValue;
    }
}
