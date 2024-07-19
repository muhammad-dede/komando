<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    //
    protected $table = 'module';

    public function permissions(){
        return $this->hasMany('App\Permission','module_id','id');
    }

    public static function getLastID() {
        $lastid = Module::orderBy('id', 'DESC')->first();

        if ($lastid == null) {
            $id = 1;
        } else {
            $id = $lastid->id + 1;
        }

        return $id;
    }

}
