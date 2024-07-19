<?php
namespace App\Models\Liquid;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LiquidPesertaAtasan extends Model
{
    protected $table = 'v_liquid_peserta_atasan';

    protected $primaryKey = null;

    public function scopeForYear(Builder $builder, $year)
    {
        $builder->where('tahun', $year);
    }

    public function scopeForUnit(Builder $builder, $unitCode)
    {
        if ($unitCode) {
            $builder->where('business_area', $unitCode);
        }
    }

    public function scopeForCompany(Builder $builder, $companyCode)
    {
        if ($companyCode) {
            $builder->where('company_code', $companyCode);
        }
    }

    public function getRekapFeedback()
    {
        $result = [
            'kelebihan_lainnya' => [],
            'kekurangan_lainnya' => [],
            'harapan' => [],
            'saran' => [],
        ];

        $liquidPeserta = LiquidPeserta::query()
            ->where('liquid_id', $this->liquid_id)
            ->where('atasan_id', $this->pernr)
            ->get();

        if ($liquidPeserta->isEmpty()) {
            return $result;
        }

        foreach ($liquidPeserta as $peserta) {
            if ($peserta->feedback) {
                array_push($result['kelebihan_lainnya'], $peserta->feedback->kelebihan_lainnya);
                array_push($result['kekurangan_lainnya'], $peserta->feedback->kekurangan_lainnya);
                array_push($result['harapan'], $peserta->feedback->harapan);
                array_push($result['saran'], $peserta->feedback->saran);
            }
        }

        return $result;
    }
}
