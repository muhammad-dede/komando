<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    //

    public function users(){
        return $this->belongsToMany('App\User');
    }

    public function getLastID(){
        $lastid = $this->all()->sortByDesc('id')->first();
        if($lastid==null) $id = 1;
        else $id = $lastid->id + 1;

        return $id;
    }

    public function setIdAttribute($attrValue){
        $this->attributes['id'] = (string) $attrValue;
    }
}
