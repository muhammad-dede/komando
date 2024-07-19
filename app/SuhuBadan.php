<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuhuBadan extends Model
{
    protected $table = 'suhu_badan';
    protected $dates = ['tanggal'];

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
