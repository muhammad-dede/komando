<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusVolunteer extends Model
{
    protected $table = 'status_volunteer';

    public function volunteer(){
        return $this->belongsTo('App\Volunteer', 'volunteer_id', 'id');
    }

    public function approver(){
        return $this->belongsTo('App\User', 'approver_id', 'id');
    }

    public function setVolunteerIdAttribute($attrValue){
        $this->attributes['volunteer_id'] = (string) $attrValue;
    }

    public function setApproverIdAttribute($attrValue){
        $this->attributes['approver_id'] = (string) $attrValue;
    }

    public static function log($volunteer_id, $message, $status, $approver_id=''){
        $log                = new StatusVolunteer();
        $log->volunteer_id  = $volunteer_id;
        $log->message       = $message;
        $log->status        = $status;
        $log->approver_id   = $approver_id;
        $log->save();

        return $log;
    }
}
