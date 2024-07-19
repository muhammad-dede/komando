<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DahanProfesi extends Model
{
    protected $table = 'm_dahan_profesi';

    protected $fillable = [
        'id',
        'proses_bisnis_id',
        'pohon_profesi_id',
        'kode',
        'description',
        'status'
    ];
}
