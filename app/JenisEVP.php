<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisEVP extends Model
{
    protected $table = 'm_jenis_evp';

    public function evp(){
        return $this->hasMany('App\EVP', 'jenis_evp_id', 'id');
    }
}
