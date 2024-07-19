<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kompetensi extends Model
{
    protected $table = 'm_kompetensi';
    
    public function levelKompetensi()
    {
        return $this->hasMany('App\LevelKompetensi','kompetensi_id','id');
    }

    
}
