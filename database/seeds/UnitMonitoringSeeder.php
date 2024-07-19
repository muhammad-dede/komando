<?php

use App\UnitMonitoring;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitMonitoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // truncate table m_unit_monitoring
        DB::table('m_unit_monitoring')->truncate();

        // read file unit_monitoring_coc.csv
        $csvFile = fopen(base_path('database/data/unit_monitoring_coc.csv'), 'r');
        $firstLine = true;

        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }

            // insert data ke table m_unit_monitoring
            UnitMonitoring::create([
                'orgeh' => $row[0],
                'company_code' => $row[1],
                'nama_unit' => $row[2],
                'sobid' => $row[3],
                'stxt2' => $row[4],
                'target_realisasi_coc' => $row[5],
            ]);

        }
        fclose($csvFile);

        // // Divisi
        // $data = DB::table('M_STRUKTUR_ORGANISASI')
        //     ->select('objid', 'company_code', 'stext', 'sobid', 'stxt2')
        //     ->where('STEXT', 'like', 'DIV%')
        //     ->where('status', 'ACTV')
        //     ->where('COMPANY_CODE', '1000')
        //     ->get();

        // foreach ($data as $item) {
        //     UnitMonitoring::create([
        //         'orgeh' => $item->objid,
        //         'company_code' => $item->company_code,
        //         'nama_unit' => $item->stext,
        //         'sobid' => $item->sobid,
        //         'stxt2' => $item->stxt2,
        //         'target_realisasi_coc' => 95,
        //     ]);
        // }

        // // UID + UIW
        // $data = DB::table('M_STRUKTUR_ORGANISASI')
        //     ->select('objid', 'company_code', 'stext', 'sobid', 'stxt2')
        //     ->where('STEXT', 'like', 'UID%')->whereOr('STEXT', 'like', 'UIW%')
        //     ->where('status', 'ACTV')
        //     ->get();

        // foreach ($data as $item) {
        //     UnitMonitoring::create([
        //         'orgeh' => $item->objid,
        //         'company_code' => $item->company_code,
        //         'nama_unit' => $item->stext,
        //         'sobid' => $item->sobid,
        //         'stxt2' => $item->stxt2,
        //         'target_realisasi_coc' => 95,
        //     ]);
        // }

        // // UIW
        // $data = DB::table('M_STRUKTUR_ORGANISASI')
        //     ->select('objid', 'company_code', 'stext', 'sobid', 'stxt2')
        //     ->where('STEXT', 'like', 'UIW%')
        //     ->where('status', 'ACTV')
        //     ->get();

        // foreach ($data as $item) {
        //     UnitMonitoring::create([
        //         'orgeh' => $item->objid,
        //         'company_code' => $item->company_code,
        //         'nama_unit' => $item->stext,
        //         'sobid' => $item->sobid,
        //         'stxt2' => $item->stxt2,
        //         'target_realisasi_coc' => 95,
        //     ]);
        // }

        // // SUB HOLDING
        // $data = DB::table('M_STRUKTUR_ORGANISASI')
        //     ->select('objid', 'company_code', 'stext', 'sobid', 'stxt2')
        //     ->where('sobid', '10096379')->whereOr('sobid', '10091860')
        //     ->where('level', 1)
        //     ->where('status', 'ACTV')
        //     ->get();

        // foreach ($data as $item) {
        //     UnitMonitoring::create([
        //         'orgeh' => $item->objid,
        //         'company_code' => $item->company_code,
        //         'nama_unit' => $item->stext,
        //         'sobid' => $item->sobid,
        //         'stxt2' => $item->stxt2,
        //         'target_realisasi_coc' => 95,
        //     ]);
        // }

        // // ANAK PERUSAHAAN
        // $data = DB::table('M_STRUKTUR_ORGANISASI')
        //     ->select('objid', 'company_code', 'stext', 'sobid', 'stxt2')
        //     ->where('sobid', '10091860')
        //     ->where('level', 1)
        //     ->where('status', 'ACTV')
        //     ->get();

        // foreach ($data as $item) {
        //     UnitMonitoring::create([
        //         'orgeh' => $item->objid,
        //         'company_code' => $item->company_code,
        //         'nama_unit' => $item->stext,
        //         'sobid' => $item->sobid,
        //         'stxt2' => $item->stxt2,
        //         'target_realisasi_coc' => 95,
        //     ]);
        // }

    }
}
