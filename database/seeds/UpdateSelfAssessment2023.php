<?php

use App\JenjangJabatanSA;
use App\Organisasi;
use App\Profesi;
use App\StreamBusiness;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateSelfAssessment2023 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // m_stream_business
        // truncate table
        StreamBusiness::truncate();
        // insert data
        $csvFile = fopen(base_path('database/data/m_stream_business.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            StreamBusiness::create([
                'id' => $row[0],
                'kode' => $row[1],
                'description' => $row[2],
                'periode' => $row[3],
                'status' => $row[4]
            ]);
        }
        fclose($csvFile);

        // m_profesi
        // truncate table
        Profesi::truncate();
        // insert data
        $csvFile = fopen(base_path('database/data/m_profesi.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            Profesi::create([
                'id' => $row[0],
                'pohon_bisnis_id' => $row[1],
                'pohon_profesi_id' => $row[2],
                'dahan_profesi_id' => $row[3],
                'kode' => $row[4],
                'description' => $row[5],
                'stream_business_pu_id' => $row[6],
                'stream_business_npu_id' => $row[7],
                'status' => $row[8]
            ]);
        }
        fclose($csvFile);

        // m_jenjang_jabatan
        // truncate table
        JenjangJabatanSA::truncate();
        // insert data
        $csvFile = fopen(base_path('database/data/m_jenjang_jabatan.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            JenjangJabatanSA::create([
                'id' => $row[0],
                'main_group' => $row[1],
                'group' => $row[2],
                'sub_group' => $row[3],
                'description' => $row[4]
            ]);
        }
        fclose($csvFile);

        // m_organisasi
        // truncate table
        Organisasi::truncate();
        // insert data
        $csvFile = fopen(base_path('database/data/m_organisasi.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            Organisasi::create([
                'id' => $row[0],
                'description' => $row[1],
                'status' => $row[2]
            ]);
        }
        fclose($csvFile);

        // p_dahan_stream_pu
        // truncate table
        DB::table('p_dahan_stream_pu')->truncate();
        // insert data
        $csvFile = fopen(base_path('database/data/p_dahan_stream_pu.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            DB::table('p_dahan_stream_pu')->insert([
                'stream_business_id' => $row[0],
                'dahan_profesi_id' => $row[1]
            ]);
        }
        fclose($csvFile);

        // p_dahan_stream_npu
        // truncate table
        DB::table('p_dahan_stream_npu')->truncate();
        // insert data
        $csvFile = fopen(base_path('database/data/p_dahan_stream_npu.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            DB::table('p_dahan_stream_npu')->insert([
                'stream_business_id' => $row[0],
                'dahan_profesi_id' => $row[1]
            ]);
        }
        fclose($csvFile);


    }
}
