<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PelanggaranCoc extends Model
{
    protected $table = 'pelanggaran_coc';

    public function pelanggaran(){
        return $this->belongsTo('App\Pelanggaran','pelanggaran_id','id');
    }

    public function coc(){
        return $this->belongsTo('App\Coc','coc_id','id');
    }
}
