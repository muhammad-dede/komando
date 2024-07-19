<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model 
{

    protected $table = 'attachment';
    public $timestamps = true;

    public function coc()
    {
        return $this->belongsTo('App\Coc', 'coc_id', 'id');
    }

    public function setCocIdAttribute($attrValue){
        $this->attributes['coc_id'] = (string) $attrValue;
    }

}