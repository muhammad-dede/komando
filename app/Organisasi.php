<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
    protected $table = 'm_organisasi';

    protected $fillable = [
        'id',
        'description',
        'status'
    ];
}
