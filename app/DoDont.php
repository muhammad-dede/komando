<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoDont extends Model
{
    protected $table = 'm_do_dont';

    public function perilaku(){
        return $this->hasMany('App\PerilakuDoDont', 'do_dont_id', 'id');
    }
}
