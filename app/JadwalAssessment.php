<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JadwalAssessment extends Model
{
    protected $table = 'jadwal_assessment';

    public function peserta()
    {
        return $this->hasMany('App\PesertaAssessment','jadwal_id','id');
    }

    public function dirkom()
    {
        return $this->belongsTo('App\Dirkom','dirkom_id','id');
    }
}
