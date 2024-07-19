<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisMateri extends Model
{
    protected $table = 'm_jenis_materi';

    public function materi(){
        return $this->hasMany('App\Materi','jenis_materi_id','id');
    }
}
