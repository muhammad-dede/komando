<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LevelKompetensi extends Model
{
    protected $table = 'm_level_kompetensi';
    
    public function kompetensi()
    {
        return $this->belongsTo('App\Kompetensi','kompetensi_id','id');
    }
}
