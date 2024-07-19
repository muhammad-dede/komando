<?php

use App\PesertaAssessment;
use Illuminate\Database\Seeder;

class UpdatePesertaSelfAssessment2023 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // peserta_assessment
        // truncate table where jadwal_id = 3
        PesertaAssessment::where('jadwal_id', 3)->delete();

        // insert data
        $csvFile = fopen(base_path('database/data/peserta_assessment_2023_2.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            PesertaAssessment::create([
                // 'id' => $row[0],
                'jadwal_id' => $row[1],
                'jabatan_id' => $row[2],
                'nip_pegawai' => $row[3],
                'nama_pegawai' => $row[4],
                'jabatan_pegawai' => $row[5],
                'company_code' => $row[6],
                'business_area' => $row[7],
                'kode_posisi' => $row[8],
                'posisi' => $row[9],
                'nip_verifikator' => $row[10],
                'nama_verifikator' => $row[11],
                'jabatan_verifikator' => $row[12],
                'kode_posisi_verifikator' => $row[13],
                'periode' => 2023,
                // 'status' => $row[14]
            ]);
        }
        fclose($csvFile);
    }
}
