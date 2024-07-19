<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KamusLevel extends Model
{
    protected $table = 'm_kamus_level';

    protected $fillable = [
        'id',
        'dirkom_id',
        'level',
        'tingkat_kecakapan',
        'pedoman_kriteria_kinerja',
        'taksonomi_umum',
        'status'
    ];
}
