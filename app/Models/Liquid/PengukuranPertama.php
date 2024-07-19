<?php

namespace App\Models\Liquid;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengukuranPertama extends Model
{
    use Auditable,
        SoftDeletes;

    protected $table = 'pengukuran_pertama';

    protected $fillable = [
        'resolusi',
        'alasan',
        'status',
        'created_by',
        'modified_by',
        'deleted_by',
        'liquid_peserta_id',
    ];

    protected $casts = [
        'resolusi' => 'array',
        'alasan' => 'array',
    ];

    public function liquidPeserta()
    {
        return $this->belongsTo(LiquidPeserta::class, 'liquid_peserta_id');
    }

    public function penilaian()
    {
        $penilaian = [];

        foreach ($this->resolusi as $resolusi) {
            list($kkDetail, $skor) = explode(':', $resolusi);
            $penilaian[$kkDetail] = $skor;
        }

        return $penilaian;
    }
}
