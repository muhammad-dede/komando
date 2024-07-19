<?php

use App\Enum\ConfigLabelEnum;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        foreach (ConfigLabelEnum::getAllKey() as $key) {
            $data[] = [
                'keys' => $key,
                'sort_translasi' => $this->getShort($key),
                'translasi' => $this->getTrans($key),
                'status' => ConfigLabelEnum::AKTIF,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::transaction(function () use ($data) {
            DB::table('config_label')
                ->insert($data);
        });
    }

    protected function getShort($key)
    {
        switch ($key) {
            case ConfigLabelEnum::KEY_KELEBIHAN:
                $result = 'Most Strength';
                break;

            case ConfigLabelEnum::KEY_KEKURANGAN:
                $result = 'Most OFI';
                break;

            case ConfigLabelEnum::KEY_SARAN:
                $result = 'Saran Bawahan terhadap Most OFI';
                break;

            case ConfigLabelEnum::KEY_INDIKATOR:
                $result = 'Pengukuran Kedua';
                break;

            case ConfigLabelEnum::KEY_FORM_PENILAIAN_1:
                $result = 'Pengukuran Pertama';
                break;

            case ConfigLabelEnum::KEY_FORM_PENILAIAN_2:
                $result = 'Saran';
                break;
            
            case ConfigLabelEnum::KEY_USULAN_ATASAN:
                $result = 'Daftar Usulan Atasan';
                break;

            default:
                // do nothing
                break;
        }

        return $result;
    }

    protected function getTrans($key)
    {
        switch ($key) {
            case ConfigLabelEnum::KEY_KELEBIHAN:
                $result = 'Most Strength';
                break;

            case ConfigLabelEnum::KEY_KEKURANGAN:
                $result = 'Most of Improvement';
                break;

            case ConfigLabelEnum::KEY_SARAN:
                $result = 'Saran Bawahan terhadap Most OFI';
                break;

            case ConfigLabelEnum::KEY_INDIKATOR:
                $result = 'Pengukuran Kedua';
                break;

            case ConfigLabelEnum::KEY_FORM_PENILAIAN_1:
                $result = 'Pengukuran Pertama';
                break;

            case ConfigLabelEnum::KEY_FORM_PENILAIAN_2:
                $result = 'Saran';
                break;
            
            case ConfigLabelEnum::KEY_USULAN_ATASAN:
                $result = 'Daftar Usulan Atasan';
                break;

            default:
                // do nothing
                break;
        }

        return $result;
    }
}
