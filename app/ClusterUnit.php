<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClusterUnit extends Model
{
    protected $table = 'cluster_unit';

    public function businessArea(){
        return $this->belongsTo('App\BusinessArea', 'business_area', 'business_area');
    }
}
