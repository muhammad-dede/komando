<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LevelKompetensiJabatan extends Model
{
    protected $table = 'level_kompetensi_jabatan';
    
    public function jabatan()
    {
        return $this->belongsTo('App\JabatanSelfAssessment','jabatan_id','id');
    }
    
    public function kompetensi()
    {
        return $this->belongsTo('App\Kompetensi','kompetensi_id','id');
    }
}
