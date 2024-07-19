<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenjangJabatanSA extends Model
{
    protected $table = 'm_jenjang_jabatan';

    protected $fillable = [
        'id',
        'main_group',
        'group',
        'sub_group',
        'description',
        'status'
    ];
}
