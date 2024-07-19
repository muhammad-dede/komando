<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class Activity extends Model
{
    protected $table = 'activity_log';

    public function users(){
        return $this->belongsTo('Appp\User', 'user_id', 'id');
    }

    public static function log($activity, $type=''){
        $agent = new Agent();
        $log                = new Activity();
        $log->username       = Auth::user()->username;
        $log->user_id       = Auth::user()->id;
        $log->text          = $activity;
        $log->type          = $type; // success, danger, info, warning
        $log->ip_address    = \Request::ip();
        $log->server_ip     = env('SERVER_IP', '-');
        $log->server_name     = env('SERVER', '-');
        
        $log->device = $agent->device();
        $log->platform = $agent->platform();
        $log->browser = $agent->browser();

        // $log->agent     = $device.' / '.$platform.' / '.$browser;

        $log->save();
    }

    public function setUserIdAttribute($attrValue){
        $this->attributes['user_id'] = (string) $attrValue;
    }

    public static function updateActivityLog(){
        $users = User::all();
        foreach($users as $user){
            echo $user->username2.' : <br>';
            foreach($user->activitiesUsername()->whereNull('user_id')->get() as $act){
                $act->user_id = $user->id;
                $act->save();
                echo $act->text.' | '.$user->id.'<br>';
            }
            echo '<br>';
        }
        return 'FINISH';
    }
}
