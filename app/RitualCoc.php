<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RitualCoc extends Model
{
    protected $table = 'ritual_coc';

    public function coc()
    {
        return $this->belongsTo('App\Coc','coc_id','id');
    }

    public function perilaku(){
        return $this->belongsTo('App\PerilakuDoDont', 'perilaku_id', 'id');
    }

}
