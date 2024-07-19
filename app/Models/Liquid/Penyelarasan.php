<?php

namespace App\Models\Liquid;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penyelarasan extends Model
{
    use SoftDeletes,
        Auditable;

    protected $table = 'penyelarasan';

    protected $fillable = [
        'resolusi',
        'catatan_kekurangan',
        'date_start',
        'date_end',
        'tempat',
        'keterangan',
        'created_by',
        'modified_by',
        'deleted_by',
        'liquid_id',
        'atasan_id',
        'aksi_nyata',
        'keterangan_aksi_nyata',
    ];

    protected $casts = [
        'resolusi' => 'array',
        'catatan_kekurangan' => 'array',
        'aksi_nyata' => 'array',
        'keterangan_aksi_nyata' => 'array',
    ];

    protected $dates = [
        'date_start',
        'date_end',
    ];

    public function liquid()
    {
        return $this->belongsTo(Liquid::class, 'liquid_id');
    }

    public static function getResolusiAsArray(Liquid $liquid, $atasanId)
    {
        $penyelarasan = static::query()
            ->where('atasan_id', $atasanId)
            ->where('liquid_id', $liquid->getKey())
            ->first();

        if ($penyelarasan) {
            return KelebihanKekuranganDetail::query()
                ->withTrashed()
                ->whereIn('id', $penyelarasan->resolusi)
                ->pluck('deskripsi_kelebihan', 'id');
        }

        return collect([]);
    }
}
