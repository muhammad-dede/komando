<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleProduction extends Model
{
    protected $connection = 'prod';
    protected $table = 'roles';

    public function users(){
        return $this->belongsToMany('App\UserProduction', 'role_user', 'role_id','user_id');
    }
}
