<?php

use App\Models\MediaKit;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosterFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $user = User::find(1);
            $mostOfi = './database/seeds/poster/most-ofi.jpeg';
            $mostStrength = './database/seeds/poster/most-strength.jpeg';

            MediaKit::create([
                'judul' => 'most-ofi',
                'jenis' => 'POSTER',
                'status' => 'ACTIVE',
                'created_by' => $user->id,
            ])->addMedia($mostOfi)->toMediaLibrary();

            MediaKit::create([
                'judul' => 'most-strength',
                'jenis' => 'POSTER',
                'status' => 'ACTIVE',
                'created_by' => $user->id,
            ])->addMedia($mostStrength)->toMediaLibrary();
        });
    }
}
