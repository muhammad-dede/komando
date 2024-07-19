<?php

namespace App\Enum;

class PengukuranPertamaEnum
{
    const RS = 'RENDAH_SEKALI';
    const R = 'RENDAH';
    const CT = 'CUKUP_TINGGI';
    const T = 'TINGGI';
    const ST = 'SANGAT_TINGGI';
    
    public static function getPenilaianValue($val)
    {
        switch ($val) {
            case static::RS:
                return 1;
                break;
            case static::R:
                return 2;
                break;
            case static::CT:
                return 3;
                break;
            case static::T:
                return 4;
                break;
            case static::ST:
                return 5;
                break;
        }
    }
}
