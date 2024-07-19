<?php

use App\ValidasiCV;
use Illuminate\Database\Seeder;

class ValidasiCVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // validasi_cv
        // truncate table
        ValidasiCV::truncate();
        // insert data
        $csvFile = fopen(base_path('database/data/validasi_cv.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            ValidasiCV::create([
                'nip' => $row[0],
                'kelengkapan_id' => $row[1],
                'jumlah' => $row[2],
                'progress' => $row[3],
                'status' => $row[4],
            ]);
        }
        fclose($csvFile);
    }
}
