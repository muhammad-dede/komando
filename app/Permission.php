<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $fillable = ['id'];

    public function module(){
        return $this->belongsTo('App\Module','module_id','id');
    }

    public function getLastID(){
        $lastid = collect($this->all())->sortByDesc('id')->first();
        if($lastid==null) $id = 1;
        else $id = $lastid->id + 1;

        return $id;
    }

    public function setIdAttribute($attrValue){
        $this->attributes['id'] = (string) $attrValue;
    }
}
