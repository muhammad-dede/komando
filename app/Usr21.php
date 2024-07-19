<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usr21 extends Model
{
    protected $table = 'usr21';

    public function adr6()
    {
        return $this->hasOne('App\Adr6','persnumber','persnumber');
    }
}
