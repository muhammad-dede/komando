<?php

namespace App\Models\Liquid;

use App\Models\Traits\Auditable;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\Media;

class ActivityLogBook extends Model implements HasMedia
{
    use Auditable,
        SoftDeletes;
    use HasMediaTrait;

    protected $table = 'activity_log_book';
    protected $fillable = [
        'resolusi',
        'start_date',
        'end_date',
        'nama_kegiatan',
        'tempat_kegiatan',
        'keterangan',
        'created_by',
        'modified_by',
        'deleted_by',
        'liquid_id',
    ];
    protected $dates = [
        'start_date',
        'end_date',
    ];
    protected $casts = [
        'resolusi' => 'array',
    ];

    public function liquid()
    {
        return $this->belongsTo(Liquid::class, 'liquid_id');
    }

    public function atasan()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getSnapshotAtasanAttribute()
    {
        if (isset($this->atasan->strukturJabatan)) {
            $pernAtasan = $this->atasan->strukturJabatan->pernr;
            $liquidPeserta = LiquidPeserta::where('liquid_id', $this->liquid_id)->where('atasan_id', $pernAtasan)->first();

            return $liquidPeserta->getSnapshotAtasan();
        }

        return [];
    }

    public function getResolusi()
    {
        return KelebihanKekuranganDetail::withTrashed()
            ->whereIn('id', $this->resolusi)
            ->get()
            ->pluck('deskripsi_kelebihan', 'id');
    }

    public function getMediaByType($type)
    {
        return $this->getMedia()->filter(function (Media $item) use ($type) {
            $type = (array) $type;

            return in_array(strtolower($item->getExtensionAttribute()), $type);
        });
    }

    public function presentLightGallery()
    {
        $images = $this->getMediaByType(['png', 'jpg', 'gif']);
        $data = [];
        foreach ($images as $image) {
            $data[] = ['src' => $image->getUrl()];
        }

        return $data;
    }
    
    public function creatorAtasan()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
