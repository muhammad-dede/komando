<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PegawaiAktif extends Model
{
    protected $table = 'pegawai_aktif';

    public function user(){
        return $this->hasOne('App\User','nip','nip');
    }
}
