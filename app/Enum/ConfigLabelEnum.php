<?php

namespace App\Enum;

class ConfigLabelEnum
{
    const AKTIF = '1';
    const TIDAK_AKTIF = '0';
    const KEY_KELEBIHAN = 'kelebihan';
    const KEY_KEKURANGAN = 'kekurangan';
    const KEY_SARAN = 'saran';
    const KEY_INDIKATOR = 'indikator';
    const KEY_FORM_PENILAIAN_1 = 'form_penilaian_1';
    const KEY_FORM_PENILAIAN_2 = 'form_penilaian_2';
    const KEY_USULAN_ATASAN = 'usulan_atasan';

    public static function getAllKey()
    {
        return [
            self::KEY_KELEBIHAN,
            self::KEY_KEKURANGAN,
            self::KEY_SARAN,
            self::KEY_INDIKATOR,
            self::KEY_FORM_PENILAIAN_1,
            self::KEY_FORM_PENILAIAN_2,
            self::KEY_USULAN_ATASAN,
        ];
    }
}
