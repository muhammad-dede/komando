<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PohonProfesi extends Model
{
    protected $table = 'm_pohon_profesi';

    protected $fillable = [
        'id',
        'pohon_bisnis_id',
        'kode',
        'description',
        'status'
    ];
}
