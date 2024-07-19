<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentPegawai extends Model
{
    protected $table = 'assessment_pegawai';

    public function kompetensi()
    {
        return $this->belongsTo('App\Kompetensi','kompetensi_id','id');
    }

    public function peserta()
    {
        return $this->belongsTo('App\PesertaAssessment','peserta_id','id');
    }
}
