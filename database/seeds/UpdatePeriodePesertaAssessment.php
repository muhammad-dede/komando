<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatePeriodePesertaAssessment extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // update periode peserta_assessment to 2021 where jadwal_id = 1
        DB::table('peserta_assessment')->where('jadwal_id', 1)->update(['periode' => 2021]);

        // update periode peserta_assessment to 2022 where jadwal_id = 2
        DB::table('peserta_assessment')->where('jadwal_id', 2)->update(['periode' => 2022]);
    }
}
