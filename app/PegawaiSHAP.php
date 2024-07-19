<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PegawaiSHAP extends Model
{
    protected $table = 'pegawai_shap';
    public $timestamps = true;

    public function dataAD()
    {
        return $this->hasOne('App\PegawaiSHAPAD', 'nip', 'nip');
    }
}
