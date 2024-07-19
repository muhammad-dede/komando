<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $table = 'volunteer';

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function evp(){
        return $this->belongsTo('App\EVP', 'evp_id', 'id');
    }

    public function statusVolunteer(){
        return $this->hasMany('App\StatusVolunteer', 'volunteer_id', 'id');
    }

    public function setEvpIdAttribute($attrValue){
        $this->attributes['evp_id'] = (string) $attrValue;
    }

    public function setUserIdAttribute($attrValue){
        $this->attributes['user_id'] = (string) $attrValue;
    }

    public function activityLog(){
        return $this->hasMany('App\ActivityLog','volunteer_id', 'id');
    }
}
