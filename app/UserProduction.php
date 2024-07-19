<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProduction extends Model
{
    protected $connection = 'prod';
    protected $table = 'users';

    public function roles()
    {
        return $this->belongsToMany('App\RoleProduction', 'role_user', 'user_id','role_id');
    }
}
