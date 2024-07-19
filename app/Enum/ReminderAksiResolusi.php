<?php

namespace App\Enum;

class ReminderAksiResolusi
{
    const MINGGUAN = 'MINGGUAN';
    const DUA_MINGGU = 'DUA_MINGGU';
    const BULANAN = 'BULANAN';
    const DUA_BULAN = 'DUA_BULAN';
    const TIGA_BULAN = 'TIGA_BULAN';

    public static function toDropdownArray()
    {
        return [
            static::MINGGUAN => '1 Minggu Sekali',
            static::DUA_MINGGU => '2 Minggu Sekali',
            static::BULANAN => '1 Bulan Sekali',
            static::DUA_BULAN => '2 Bulan Sekali',
            static::TIGA_BULAN => '3 Bulan Sekali',
        ];
    }

    public static function keys()
    {
        return array_keys(static::toDropdownArray());
    }
    
    public static function toNumber($label)
    {
        $numberedLabels = [
            static::MINGGUAN => 7,
            static::DUA_MINGGU => 14,
            static::BULANAN => 30,
            static::DUA_BULAN => 60,
            static::TIGA_BULAN => 90,
        ];
        
        return $numberedLabels[$label];
    }
}
