<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisPelanggaran extends Model
{
    protected $table = 'm_jenis_pelanggaran';

    public function pelanggaran(){
        return $this->hasMany('App\Pelanggaran','jenis_pelanggaran_id','id');
    }
}
