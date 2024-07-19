<?php

use App\JabatanSelfAssessment;
use App\LevelKompetensi;
use App\LevelKompetensiJabatan;
use Illuminate\Database\Seeder;

class PesertaAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // m_jabatan_self_asmnt
        // truncate table where dirkom_id = 2
        JabatanSelfAssessment::where('dirkom_id', 2)->delete();
        // insert data
        $csvFile = fopen(base_path('database/data/m_jabatan_self_asmnt.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            JabatanSelfAssessment::create([
                'id' => $row[0],
                'sebutan_jabatan' => $row[1],
                'organisasi_id' => $row[2],
                'organisasi' => $row[3],
                'jenjang_jabatan_id' => $row[4],
                'jenjang_jabatan' => $row[5],
                'pemimpin_unit' => $row[8],
                'stream_business_id' => $row[9],
                'stream_business' => $row[10],
                'profesi_id' => $row[11],
                'profesi' => $row[12],
                'status' => $row[13],
                'dirkom_id' => $row[14]
            ]);
        }
        fclose($csvFile);

        // m_level_kompetensi
        // truncate table where dirkom_id = 2
        LevelKompetensi::where('dirkom_id', 2)->delete();
        // insert data
        $csvFile = fopen(base_path('database/data/m_level_kompetensi.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            LevelKompetensi::create([
                'id' => $row[0],
                'kode_kompetensi' => $row[1],
                'kompetensi_id' => $row[2],
                'level' => $row[3],
                'perilaku' => $row[4],
                'contoh' => $row[5],
                'status' => $row[6],
                'dirkom_id' => $row[7]
            ]);
        }
        fclose($csvFile);
        
        // level_kompetensi_jabatan
        // truncate table where dirkom_id = 2
        LevelKompetensiJabatan::where('dirkom_id', 2)->delete();
        // insert data
        $csvFile = fopen(base_path('database/data/level_kompetensi_jabatan.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            LevelKompetensiJabatan::create([
                'id' => $row[0],
                'jabatan_id' => $row[1],
                'kode_kompetensi' => $row[2],
                'kompetensi_id' => $row[3],
                'level' => $row[4],
                'status' => $row[5],
                'dirkom_id' => $row[6]
            ]);
        }
        fclose($csvFile);

    }
}
