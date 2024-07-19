<?php

namespace App\Models\Liquid;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use SoftDeletes,
        Auditable;

    protected $fillable = [
        'atasan',
        'kelebihan',
        'kekurangan',
        'harapan',
        'saran',
        'new_kelebihan',
        'new_kekurangan',
        'status',
        'created_by',
        'modified_by',
        'deleted_by',
        'liquid_peserta_id',
        'alasan_kelebihan',
        'alasan_kekurangan',
    ];

    protected $casts = [
        'kelebihan' => 'array',
        'kekurangan' => 'array',
        'new_kelebihan' => 'array',
        'new_kekurangan' => 'array',
        'alasan_kelebihan' => 'array',
        'alasan_kekurangan' => 'array',
    ];

    public function liquidPeserta()
    {
        return $this->belongsTo(LiquidPeserta::class, 'liquid_peserta_id');
    }

    public function getKelebihanAsArray()
    {
        $kelebihan = KelebihanKekuranganDetail::withTrashed()
            ->whereIn('id', $this->kelebihan)
            ->pluck('deskripsi_kelebihan', 'id');
        if ($this->kelebihan_lainnya) {
            $kelebihan['__OTHER__'] = $this->kelebihan_lainnya;
        }

        return $kelebihan;
    }

    public function getKekuranganAsArray()
    {
        $kekurangan = KelebihanKekuranganDetail::withTrashed()
            ->whereIn('id', $this->kekurangan)
            ->pluck('deskripsi_kekurangan', 'id');
        if ($this->kekurangan_lainnya) {
            $kekurangan['__OTHER__'] = $this->kekurangan_lainnya;
        }

        return $kekurangan;
    }

    public function getKelebihanLainnyaAttribute()
    {
        return is_string($this->new_kelebihan)
            ? $this->new_kelebihan
            : array_first($this->new_kelebihan);
    }

    public function getKekuranganLainnyaAttribute()
    {
        return is_string($this->new_kekurangan)
            ? $this->new_kekurangan
            : array_first($this->new_kekurangan);
    }
}
