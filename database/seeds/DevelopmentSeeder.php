<?php

use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        // call pending migration
        \Artisan::call('migrate');

        // and refresh database view
        \Artisan::call('app:refresh-db-view');

        // seed permission untuk mengakses menu Kelebihan Kekurangan
        $this->call(KelebihanKekuranganPermissionSeeder::class);
        $this->call(LiquidPermissionSeeder::class);

        // prepare 1 active LIQUID
        $this->call(KelebihanKekuranganSeeder::class);
        $liquid = factory(\App\Models\Liquid\Liquid::class)
            ->create([
                'kelebihan_kekurangan_id' => \App\Models\Liquid\KelebihanKekurangan::getActiveId(),
                'status' => \App\Enum\LiquidStatus::PUBLISHED
            ]);
        app(\App\Services\LiquidService::class)->syncBusinessAreaDanPeserta($liquid, ['7113']);

        // reset user passwords, for development purpose only
        \App\User::query()->update(['password' => bcrypt('asdf1234')]);
    }
}
