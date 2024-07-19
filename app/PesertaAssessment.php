<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PesertaAssessment extends Model
{
    protected $table = 'peserta_assessment';

    public function jadwal()
    {
        return $this->belongsTo('App\JadwalAssessment','jadwal_id','id');
    }

    public function jabatanPeserta()
    {
        return $this->belongsTo('App\JabatanSelfAssessment','jabatan_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','nip_pegawai','nip');
    }

    public function companyCode()
    {
        return $this->belongsTo('App\CompanyCode','company_code','company_code');
    }

    public function businessArea()
    {
        return $this->belongsTo('App\BusinessArea','business_area','business_area');
    }

    public function strukturPosisi()
    {
        return $this->belongsTo('App\StrukturPosisi','kode_posisi','objid');
    }

    public function getPenilaianPegawai($kode_kompetensi)
    {
        $penilaian = AssessmentPegawai::where('peserta_id',$this->id)->where('kode_kompetensi', $kode_kompetensi)->first();

        return $penilaian;
    }

    public function assessmentPegawai()
    {
        return $this->hasMany('App\AssessmentPegawai', 'peserta_id', 'id');
    }

    public function getJumlahKompetensi(){
        // dd($this->jabatanPeserta->kompetensi->count());
        if($this->jabatanPeserta!=null)
            return $this->jabatanPeserta->kompetensi->count();

        return 0;
    }

    public function getJumlahGap(){
        // dd($this->jabatanPeserta->kompetensi->count());
        if($this->assessmentPegawai!=null){
            return $this->assessmentPegawai()->where('gap_level','<',0)->count();
        }

        return 0;
    }

}
