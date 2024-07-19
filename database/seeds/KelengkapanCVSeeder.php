<?php

use App\KelengkapanCV;
use Illuminate\Database\Seeder;

class KelengkapanCVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // m_kelengkapan_cv
        // truncate table
        KelengkapanCV::truncate();
        // insert data
        $csvFile = fopen(base_path('database/data/m_kelengkapan_cv.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            KelengkapanCV::create([
                'id' => $row[0],
                'description' => $row[1],
                'target' => $row[2]
            ]);
        }
        fclose($csvFile);
    }
}
