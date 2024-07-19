<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupPLN extends Model
{
    protected $table = 'm_pln_group';
    protected $fillable = ['kode', 'description'];
    public $timestamps = true;
}
