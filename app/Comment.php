<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model 
{

    protected $table = 'comment_coc';
    public $timestamps = true;

    public function coc()
    {
        return $this->belongsTo('App\Coc', 'coc_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function setCocIdAttribute($attrValue){
        $this->attributes['coc_id'] = (string) $attrValue;
    }

    public function setUserIdAttribute($attrValue){
        $this->attributes['user_id'] = (string) $attrValue;
    }

}