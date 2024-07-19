<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisEVPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_jenis_evp')->insert(['description' => 'Nasional']);
        DB::table('m_jenis_evp')->insert(['description' => 'Lokal']);
    }
}
