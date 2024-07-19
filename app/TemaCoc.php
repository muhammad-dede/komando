<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemaCoc extends Model 
{

    protected $table = 'tema_coc';
    protected $dates = ['start_date', 'end_date'];
    public $timestamps = true;


    public function tema()
    {
        return $this->belongsTo('App\Tema', 'tema_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo('App\Event', 'event_id', 'id');
    }

    public function createdBy(){
//        return $this->belongsTo('App\User','created_by','username');
        return $this->belongsTo('App\User','user_id_create','id');
    }

    public function updatedBy(){
//        return $this->belongsTo('App\User','updated_by','username');
        return $this->belongsTo('App\User','user_id_update','id');
    }

}