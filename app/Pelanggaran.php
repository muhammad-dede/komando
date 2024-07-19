<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table = 'm_pelanggaran';

    public function jenisPelanggaran(){
        return $this->belongsTo('App\JenisPelanggaran','jenis_pelanggaran_id','id');
    }

    public function pelanggaranCoc(){
        return $this->hasMany('App\PelanggaranCoc','pelanggaran_id','id');
    }
}
