<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttachmentMateri extends Model 
{

    protected $table = 'attachment_materi';
    public $timestamps = true;

    public function materi()
    {
        return $this->belongsTo('App\Materi', 'materi_id', 'id');
    }

}