<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KelengkapanCV extends Model
{
    protected $table = 'm_kelengkapan_cv';

    protected $fillable = [
        'id',
        'description',
        'target',
        'status'
    ];
}
