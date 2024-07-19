<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_log_evp';

    protected $dates = ['waktu'];

    public function volunteer(){
        return $this->belongsTo('App\Volunteer', 'volunteer_id', 'id');
    }

    public function evp(){
        return $this->belongsTo('App\EVP', 'evp_id', 'id');
    }

    public function setEvpIdAttribute($attrValue){
        $this->attributes['evp_id'] = (string) $attrValue;
    }

    public function setVolunteerIdAttribute($attrValue){
        $this->attributes['volunteer_id'] = (string) $attrValue;
    }
}
