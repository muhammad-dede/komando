<?php

use Illuminate\Database\Seeder;

class KelebihanKekuranganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::transaction(function () {
            \App\Models\Liquid\KelebihanKekurangan::query()
                ->update(['status' => \App\Enum\KelebihanKekuranganStatus::TIDAK_AKTIF]);

            $master = $this->readCSV(database_path('data/kelebihan_kekurangan.csv'));
            $master = array_first($master);
            $kk = \App\Models\Liquid\KelebihanKekurangan::create([
                'title' => $master[1],
                'deskripsi' => $master[2],
                'status' => \App\Enum\KelebihanKekuranganStatus::AKTIF,
                'created_by' => 1,
            ]);

            $details = $this->readCSV(database_path('data/kelebihan_kekurangan_detail.csv'));
            foreach ($details as $detail) {
                if (!is_null($detail[4]) && !is_null($detail[11])) {
                    $kkDetail = (new \App\Models\Liquid\KelebihanKekuranganDetail())->fill([
                        'deskripsi_kelebihan' => $detail[4],
                        'deskripsi_kekurangan' => $detail[11],
                        'created_by' => 1,
                    ]);
                    $kk->details()->save($kkDetail);
                }
            }
        });
    }

    protected function readCSV($csvFile)
    {
        $data = [];
        $handler = fopen($csvFile, 'r');
        while (!feof($handler)) {
            $data[] = fgetcsv($handler);
        }

        fclose($handler);

        return $data;
    }
}
