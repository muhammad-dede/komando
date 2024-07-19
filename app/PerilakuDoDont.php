<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerilakuDoDont extends Model
{
    protected $table = 'm_perilaku_dodont';

//    public function pedomanPerilaku(){
//        return $this->belongsTo('App\PedomanPerilaku', 'pedoman_perilaku_id', 'id');
//    }

    public function doDont(){
        return $this->belongsTo('App\DoDont', 'do_dont_id', 'id');
    }
}
