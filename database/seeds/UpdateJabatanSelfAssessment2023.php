<?php

use App\JabatanSelfAssessment;
use App\LevelKompetensiJabatan;
use App\PesertaAssessment;
use Illuminate\Database\Seeder;

class UpdateJabatanSelfAssessment2023 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // set status=DEL peserta assessment yang jadwal_id=2 dan jadwal_id=3
        PesertaAssessment::where('jadwal_id', 2)->orWhere('jadwal_id', 3)->update(['status' => 'DEL']);
        
        // m_jabatan_self_asmnt
        // set status INAC untuk jabatan self assessment dirkom_id = 2 yang lama untuk id=78 sampai id=1347
        JabatanSelfAssessment::where('dirkom_id', 2)->whereBetween('id', [78, 1347])->update(['status' => 'INAC']);

        // truncate table where dirkom_id = 2 dan status ACTV
        JabatanSelfAssessment::where('dirkom_id', 2)->where('status', 'ACTV')->delete();

        // insert data
        // $csvFile = fopen(base_path('database/data/m_jabatan_self_asmnt_test.csv'), 'r');
        $csvFile = fopen(base_path('database/data/m_jabatan_self_asmnt_2023_2.csv'), 'r');
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
                'status' => 'ACTV',
                'dirkom_id' => 2
            ]);
        }
        fclose($csvFile);

        // show message info console
        $this->command->info('Jabatan Self Assessment 2023 berhasil diupdate');
        
        // level_kompetensi_jabatan
        // set status INAC untuk level kompetensi jabatan dirkom_id = 2 yang lama untuk id=441 sampai id=8841
        LevelKompetensiJabatan::where('dirkom_id', 2)->whereBetween('id', [441, 8841])->update(['status' => 'INAC']);

        // truncate table where dirkom_id = 2 dan status ACTV
        LevelKompetensiJabatan::where('dirkom_id', 2)->where('status', 'ACTV')->delete();

        // insert data
        $csvFile = fopen(base_path('database/data/level_kompetensi_jabatan_2023_2.csv'), 'r');
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
                'status' => 'ACTV',
                'dirkom_id' => 2
            ]);
        }
        fclose($csvFile);

        // show message info console
        $this->command->info('Level Kompetensi Jabatan 2023 berhasil diupdate');
    }
}
