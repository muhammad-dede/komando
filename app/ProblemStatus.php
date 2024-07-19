<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemStatus extends Model
{
    protected $table = 'm_problem_status';

    public function problem(){
        return $this->hasMany('App\Problem','status', 'id');
    }
}
