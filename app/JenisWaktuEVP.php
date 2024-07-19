<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisWaktuEVP extends Model
{
    protected $table = 'm_jenis_waktu_evp';

    public function evp(){
        return $this->hasMany('App\EVP', 'jenis_waktu_id', 'id');
    }
}
