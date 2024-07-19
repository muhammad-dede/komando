<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateDirkomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // m_jabatan_self_asmnt
        // set data dirkom_id to null where dirkom_id = 1
        DB::table('m_jabatan_self_asmnt')
            ->where('dirkom_id', 1)
            ->update(['dirkom_id' => null]);

        // update data dirkom_id to 1 where dirkom_id is null
        DB::table('m_jabatan_self_asmnt')
            ->whereNull('dirkom_id')
            ->update(['dirkom_id' => 1]);
        
        // m_kompetensi
        // set data dirkom_id to null where dirkom_id = 1
        DB::table('m_kompetensi')
            ->where('dirkom_id', 1)
            ->update(['dirkom_id' => null]);

        // update data dirkom_id to 1 where dirkom_id is null
        DB::table('m_kompetensi')
            ->whereNull('dirkom_id')
            ->update(['dirkom_id' => 1]);

        // m_level_kompetensi
        // set data dirkom_id to null where dirkom_id = 1
        DB::table('m_level_kompetensi')
            ->where('dirkom_id', 1)
            ->update(['dirkom_id' => null]);

        // update data dirkom_id to 1 where dirkom_id is null
        DB::table('m_level_kompetensi')
            ->whereNull('dirkom_id')
            ->update(['dirkom_id' => 1]);

        // level_kompetensi_jabatan
        // set data dirkom_id to null where dirkom_id = 1
        DB::table('level_kompetensi_jabatan')
            ->where('dirkom_id', 1)
            ->update(['dirkom_id' => null]);

        // update data dirkom_id to 1 where dirkom_id is null
        DB::table('level_kompetensi_jabatan')
            ->whereNull('dirkom_id')
            ->update(['dirkom_id' => 1]);

        // jadwal_assessment
        // set data dirkom_id to null where dirkom_id = 1
        DB::table('jadwal_assessment')
            ->where('dirkom_id', 1)
            ->update(['dirkom_id' => null]);

        // update data dirkom_id to 1 where dirkom_id is null
        DB::table('jadwal_assessment')
            ->whereNull('dirkom_id')
            ->update(['dirkom_id' => 1]);

        // jadwal_assessment
        // delete jadwal periode 2022
        DB::table('jadwal_assessment')
            ->where('periode', 2022)
            ->delete();

        // insert new data
        DB::table('jadwal_assessment')->insert([
            'id' => '2',
            'periode' => 2022,
            'tanggal_awal' => '2022-01-01',
            'tanggal_akhir' => '2022-12-31',
            'keterangan' => 'Self Assessment 2022',
            'dirkom_id' => 2
        ]);
        
    }
}
