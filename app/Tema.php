<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tema extends Model 
{

    protected $table = 'm_tema';
    public $timestamps = true;
    protected $fillable = ['tema'];

    public function coc()
    {
        return $this->hasMany('App\Coc', 'tema_id', 'id');
    }

    public function temaCoc()
    {
        return $this->hasMany('App\TemaCoc', 'tema_id', 'id');
    }

    public function setIdAttribute($attrValue){
        $this->attributes['id'] = (string) $attrValue;
    }

}