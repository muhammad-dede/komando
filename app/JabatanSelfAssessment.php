<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JabatanSelfAssessment extends Model
{
    protected $table = 'm_jabatan_self_asmnt';
    
    public function kompetensi()
    {
        return $this->hasMany('App\LevelKompetensiJabatan','jabatan_id','id');
    }

    public function dirkom()
    {
        return $this->belongsTo('App\Dirkom','dirkom_id','id');
    }

    public function profesi()
    {
        $list_profesi = LevelKompetensiJabatan::select('profesi_id', 'profesi')
                                                ->where('jabatan_id',$this->id)
                                                ->leftJoin('m_kompetensi','m_kompetensi.id','=','level_kompetensi_jabatan.kompetensi_id')
                                                ->distinct()
                                                ->orderBy('profesi', 'asc')
                                                ->get();
        return $list_profesi;
    }

    public function dahanProfesi()
    {
        $list_dahan = LevelKompetensiJabatan::select('dahan_profesi_id', 'dahan_profesi')
                                                ->where('jabatan_id',$this->id)
                                                ->leftJoin('m_kompetensi','m_kompetensi.id','=','level_kompetensi_jabatan.kompetensi_id')
                                                ->distinct()
                                                ->orderBy('dahan_profesi', 'asc')
                                                ->get();
        return $list_dahan;
    }
}
