<?php

use App\IsuNasional;
use Hamcrest\Core\Is;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssueNasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // truncate issue nasional table
        IsuNasional::truncate();

        // insert data
        $csvFile = fopen(base_path('database/data/m_issue_nasional.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            // insert issue nasional with DB
            DB::table('m_isu_nasional')->insert([
                "id" => $row['0'],
                "isu_nasional" => $row['1'],
                "header" => $row['2'],
                "sub_header" => $row['3'],
                "description" => $row['4'],
                "sanksi" => $row['5']
            ]);    

        }
        fclose($csvFile);
    }
}
