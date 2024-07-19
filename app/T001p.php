<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class T001p extends Model
{
    protected $table = 't001p';
//    protected $table = 't001p_tmp';

    public function hrp1008(){
        return $this->hasMany('App\Hrp1008', 'btrtl', 'btrtl');
    }
}
