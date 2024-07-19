<?php

namespace App\Models\Liquid;

use App\Services\LiquidService;
use Illuminate\Database\Eloquent\Model;

class LiquidReportData extends Model
{
    protected $table = 'v_liquid_rekap_liquid';

    public function peserta()
    {
        return $this->belongsTo(LiquidPeserta::class, 'atasan_id', 'atasan_id')->where('liquid_id', $this->liquid_id);
    }

    public function getPresentJenjangJabatanAttribute()
    {
        $jabatan = $this->attributes['jenjang_jabatan'];
        if (!$jabatan) {
            $jabatan = 'uncategorized';
        }

        return trans('enum.'.\App\Enum\LiquidJabatan::class.".".$jabatan);
    }

    public function getDivisiPusat()
    {
        $listDivisiPusat = app(LiquidService::class)->listDivisiPusat();

        if ($this->snapshot_orgeh_1) {
            $divisiPusat = array_get($listDivisiPusat, $this->snapshot_orgeh_1);
            if ($divisiPusat !== null) {
                return $divisiPusat;
            }
        }

        if ($this->snapshot_orgeh_2) {
            $divisiPusat = array_get($listDivisiPusat, $this->snapshot_orgeh_2);
            if ($divisiPusat !== null) {
                return $divisiPusat;
            }
        }

        if ($this->snapshot_orgeh_3) {
            $divisiPusat = array_get($listDivisiPusat, $this->snapshot_orgeh_3);
            if ($divisiPusat !== null) {
                return $divisiPusat;
            }
        }

        return null;
    }
}
