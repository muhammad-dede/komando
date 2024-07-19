<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\SurveyQuestion;
use Carbon\Carbon;

class MasterDataSurveyQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SurveyQuestion::where('status','1')->update(['status' => '0']);
        $data = [
            'question' => "Seberapa besar anda merekomendasikan atasan sebagai role model AKHLAK?",
            'status' => "1",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        DB::transaction(function () use ($data) {
            DB::table('survey_question')
                ->insert($data);
        });
    }
}
