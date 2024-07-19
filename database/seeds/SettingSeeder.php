<?php

use App\Enum\SettingFlagEnum;
use App\Models\Liquid\Settings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::transaction(function () {
            foreach (SettingFlagEnum::getAllKey() as $key) {
                Settings::updateOrInsert(['key' => $key], ['value' => $this->getShort($key)]);
            }
        });
    }

    protected function getShort($key)
    {
        switch ($key) {
            case SettingFlagEnum::ADMIN_ROOT:
                $result = 'assets/settings/User_Manual_Komando_Liquid_v1-Admin.pdf';
                break;

            case SettingFlagEnum::ADMIN_UNIT_PELAKSANA:
                $result = 'assets/settings/User_Manual_Komando_Liquid_v1-Admin.pdf';
                break;

            case SettingFlagEnum::DASHBOARD_ATASAN:
                $result = 'assets/settings/User_Manual_Komando_Liquid_v1-Atasan.pdf';
                break;

            case SettingFlagEnum::DASHBOARD_BAWAHAN:
                $result = 'assets/settings/User_Manual_Komando_Liquid_v1-Bawahan.pdf';
                break;

            case SettingFlagEnum::FAQ:
                $result = 'assets/settings/FREQUENTLY_ASKED_QUESTION(FAQ).pdf';
                break;

            default:
                // do nothing
                $result = '';
                break;
        }

        return $result;
    }
}
