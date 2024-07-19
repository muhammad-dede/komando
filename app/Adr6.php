<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adr6 extends Model
{
    protected $table = 'adr6';

    public function usr21()
    {
        return $this->belongsTo('App\Usr21','persnumber','persnumber');
    }
}
