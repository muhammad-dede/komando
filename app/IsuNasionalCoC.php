<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IsuNasionalCoC extends Model
{
    protected $table = 'isu_nasional_coc';

    public function isuNasional(){
        return $this->belongsTo('App\IsuNasional','isu_nasional_id','id');
    }

    public function coc(){
        return $this->belongsTo('App\Coc','coc_id','id');
    }
}
