<?php

use Illuminate\Database\Seeder;

class LiquidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Liquid\Liquid::class)->times(10)->create();
    }
}
