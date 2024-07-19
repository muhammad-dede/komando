<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $table='m_server';

    public function problem(){
        return $this->hasMany('App\Problem','server_id', 'id');
    }
}
