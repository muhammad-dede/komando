<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendant extends Model 
{

    protected $table = 'attendant';
    protected $dates = ['check_in'];
    public $timestamps = true;

    protected $fillable = [
        'coc_id',
        'user_id',
        'check_in',
        'status_checkin_id',
    ];

    public function coc()
    {
        return $this->belongsTo('App\Coc', 'coc_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function setCocIdAttribute($attrValue){
        $this->attributes['coc_id'] = (string) $attrValue;
    }

    public function setUserIdAttribute($attrValue){
        $this->attributes['user_id'] = (string) $attrValue;
    }

    public function statusCheckin(){
        return $this->belongsTo('App\StatusCheckIn','status_checkin_id','id');
    }

}