<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedomanPerilaku extends Model
{
    protected $table = 'm_pedoman_perilaku';

    public function perilaku(){
        return $this->hasMany('App\Perilaku', 'pedoman_perilaku_id', 'id');
    }

    public function perilakuPegawai(){
        return $this->hasMany('App\PerilakuPegawai', 'pedoman_perilaku_id', 'id');
    }

    public function pertanyaan(){
        return $this->hasMany('App\Pertanyaan', 'pedoman_perilaku_id', 'id');
    }

    public function jawabanPegawai(){
        return $this->hasMany('App\JawabanPegawai', 'pedoman_perilaku_id', 'id');
    }
}
