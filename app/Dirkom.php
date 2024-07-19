<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dirkom extends Model
{
    protected $table = 'm_dirkom';

    protected $fillable = [
        'id',
        'tahun',
        'description',
        'jumlah_level',
        'status'
    ];

}
