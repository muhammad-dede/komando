<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValidasiCV extends Model
{
    protected $table = 'validasi_cv';

    protected $fillable = [
        'id',
        'nip',
        'kelengkapan_id',
        'jumlah',
        'progress',
        'status'
    ];

    public function kelengkapan()
    {
        return $this->belongsTo('App\KelengkapanCV', 'kelengkapan_id');
    }
}