<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PohonBisnis extends Model
{
    protected $table = 'm_pohon_bisnis';

    protected $fillable = [
        'id',
        'kode',
        'description',
        'status'
    ];
}
