<?php

use Illuminate\Database\Seeder;

class JenisWaktuEVPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_jenis_waktu_evp')->insert(['description' => 'Full Time']);
        DB::table('m_jenis_waktu_evp')->insert(['description' => 'Part Time']);
    }
}
