<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class T500p extends Model
{
    protected $table = 't500p';
//    protected $table = 't500p_tmp';

    public function hrp1008(){
        return $this->hasMany('App\Hrp1008', 'persa', 'persa');
    }
}
