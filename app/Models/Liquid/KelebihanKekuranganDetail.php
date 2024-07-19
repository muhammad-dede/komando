<?php

namespace App\Models\Liquid;

use App\Enum\PilarUtamaEnum;
use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelebihanKekuranganDetail extends Model
{
    use SoftDeletes,
        Auditable;

    protected $fillable = [
        'kelebihan',
        'kekurangan',
        'parent_id',
        'deskripsi_kelebihan',
        'deskripsi_kekurangan',
        'created_by',
        'modified_by',
        'deleted_by',
        'category',
    ];
    protected $table = 'kelebihan_kekurangan_detail';
    protected $dates = ['deleted_at'];

    public function parent()
    {
        return $this->belongsTo(KelebihanKekurangan::class, 'parent_id');
    }

    public function getCategoryStringAttribute()
    {
        $array = PilarUtamaEnum::toDropdownArray();

        if (empty($this->category)) {
            return '-';
        }

        return $array[$this->category];
    }
}
