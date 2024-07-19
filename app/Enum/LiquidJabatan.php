<?php

namespace App\Enum;

class LiquidJabatan extends BaseEnum
{
    const EVP = 'EVP';
    const VP = 'VP';
    const GM = 'GM';
    const SRM = 'SRM';
    const MD_PUSAT = 'MD_PUSAT';
    const MD_UP = 'MD_UP';
    const SPV_ATAS_SUP = 'SPV_ATAS_SUP';
    const MANAGER = 'MANAGER';
    const SPV = 'SPV';
    const STAF = 'STAF';

    public static function sortedByLevel()
    {
        return [
            static::EVP,
            static::VP,
            static::GM,
            static::SRM,
            static::MD_PUSAT,
            static::MD_UP,
            static::SPV_ATAS_SUP,
            static::MANAGER,
            static::SPV,
            static::STAF,
        ];
    }

    public static function getOrder($jabatan)
    {
        return array_search($jabatan, static::sortedByLevel());
    }

    public static function toDropdownArray()
    {
        $options = parent::toDropdownArray();
        $optionsTranslated = [];
        $class = static::class;
        foreach ($options as $key => $value) {
            $optionsTranslated[$key] = trans("enum.{$class}.$key");
        }

        return $optionsTranslated;
    }
}
