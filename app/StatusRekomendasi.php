<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusRekomendasi extends Model
{
    protected $table = 'm_status_rekomendasi';

    protected $fillable = [
        'id',
        'description',
        'status'
    ];
}
