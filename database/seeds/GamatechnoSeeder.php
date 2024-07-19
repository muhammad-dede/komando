<?php

use Illuminate\Database\Seeder;

class GamatechnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleAdminHtdArea::class);
        $this->call(PermissionsSeeder::class);
        $this->call(MasterDataLabelSeeder::class);
        $this->call(MasterDataKelebihanKekuranganSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(MasterDataSurveyQuestionSeeder::class);
    }
}
