<?php

namespace App\Services;

use App\Models\Liquid\Settings;
use App\Enum\SettingFlagEnum;

class SettingService
{
    public function getSetting()
    {
        $data = [];
        foreach (SettingFlagEnum::getAllKey() as $key) {
            $value = Settings::where('key', $key)->value('value');
            if(empty($value)) {
                $value = "#";
            }
            $data[$key] = $value;
        }
        return $data;
    }
}
