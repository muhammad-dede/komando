<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model 
{

    protected $table = 'event';
    protected $dates = ['start','end'];
    public $timestamps = true;

    public function coc()
    {
        return $this->hasOne('App\Coc', 'event_id', 'id');
    }

    public function temaCoc()
    {
        return $this->hasOne('App\TemaCoc', 'event_id', 'id');
    }

}