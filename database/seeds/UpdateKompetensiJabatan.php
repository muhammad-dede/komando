<?php

use App\Kompetensi;
use Illuminate\Database\Seeder;

class UpdateKompetensiJabatan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // m_kompetensi
        // truncate table where dirkom_id = 2
        Kompetensi::where('dirkom_id', 2)->delete();
        // insert data
        $csvFile = fopen(base_path('database/data/m_kompetensi_v2.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            Kompetensi::create([
                'id' => $row[0],
                'kode' => $row[1],
                'judul_kompetensi' => $row[2],
                'judul_en' => $row[3],
                'deskripsi' => $row[4],
                'dahan_profesi_id' => $row[5],
                'kode_dahan' => $row[6],
                'dahan_profesi' => $row[7],
                'profesi_id' => $row[8],
                'kode_profesi' => $row[9],
                'profesi' => $row[10],
                'status' => $row[11],
                'dirkom_id' => $row[14]
            ]);
        }
        fclose($csvFile);
    }
}
