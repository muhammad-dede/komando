<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StreamBusiness extends Model
{
    protected $table = 'm_stream_business';

    protected $fillable = [
        'id',
        'kode',
        'description',
        'periode',
        'status'
    ];
}
