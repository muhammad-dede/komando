<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EVPLog extends Model
{
    protected $table = 'evp_log';

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function volunteer(){
        return $this->belongsTo('App\Volunteer', 'volunteer_id', 'id');
    }
}
