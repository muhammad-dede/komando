<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RundownEVP extends Model
{
    protected $table = 'rundown_evp';
    protected $dates = ['tgl_jam_awal', 'tgl_jam_akhir'];

    public function evp(){
        return $this->belongsTo('App\EVP', 'evp_id', 'id');
    }

    public function setEvpIdAttribute($attrValue){
        $this->attributes['evp_id'] = (string) $attrValue;
    }
}
