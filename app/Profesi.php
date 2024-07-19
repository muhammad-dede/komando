<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profesi extends Model
{
    protected $table = 'm_profesi';

    protected $fillable = [
        'id',
        'pohon_bisnis_id',
        'pohon_profesi_id',
        'dahan_profesi_id',
        'kode',
        'description',
        'stream_business_pu_id',
        'stream_business_npu_id',
        'status'
    ];
}
