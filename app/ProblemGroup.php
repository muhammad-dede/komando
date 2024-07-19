<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemGroup extends Model
{
    protected $table = 'm_problem_grup';

    public function problem(){
        return $this->hasMany('App\Problem','grup_id', 'id');
    }
}
