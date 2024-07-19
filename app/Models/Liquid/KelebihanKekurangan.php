<?php

namespace App\Models\Liquid;

use App\Enum\KelebihanKekuranganStatus;
use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelebihanKekurangan extends Model
{
    use Auditable;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'deskripsi',
        'status',
        'created_by',
        'modified_by',
        'deleted_by',
    ];
    protected $table = 'kelebihan_kekurangan';
    protected $dates = ['deleted_at'];

    public static function getActiveId()
    {
        return static::query()->where('status', KelebihanKekuranganStatus::AKTIF)->value('id');
    }

    public function details()
    {
        return $this->hasMany(KelebihanKekuranganDetail::class, 'parent_id', 'id')
            ->orderBy('category', 'ASC');
    }
}
