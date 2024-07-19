<?php

use App\CompanyCode;
use App\GroupPLN;
use App\Models\Liquid\BusinessArea;
use App\StrukturOrganisasi;
use Illuminate\Database\Seeder;

class GroupPLNSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // validasi_cv
        // truncate table
        GroupPLN::truncate();
        // insert data
        $csvFile = fopen(base_path('database/data/m_pln_group.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            GroupPLN::create([
                'id' => $row[0],
                'kode' => $row[1],
                'description' => $row[2],
                'company_code' => $row[3],
                'business_area' => $row[4],
                'orgeh' => $row[5],
                'nip_leader' => $row[6],
                'plans_leader' => $row[7],
                'personel_area' => $row[8],
                'personel_subarea' => $row[9]
            ]);

            // update level m_struktur_organisasi
            $shap = StrukturOrganisasi::where('objid', $row[5])->first();
            // if shap not null, update level
            if ($shap) {
                $shap->level = 1;
                $shap->save();
            }
        }
        fclose($csvFile);

        // insert company code shap
        $csvFile = fopen(base_path('database/data/m_company_code_shap.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            // check if company code exists
            $company_code = CompanyCode::where('company_code', $row[0])->first();
            if (!$company_code) {
                $company_code = new CompanyCode();

                // get last id
                $last_id = CompanyCode::orderBy('id', 'desc')->first();
                if ($last_id) {
                    $company_code->id = $last_id->id + 1;
                } else {
                    $company_code->id = 1;
                }
            }

            $company_code->company_code = $row[0];
            $company_code->description = $row[1];
            $company_code->status = 'ACTV';
            $company_code->save();
        }
        fclose($csvFile);

        // insert business area shap
        $csvFile = fopen(base_path('database/data/m_business_area_shap.csv'), 'r');
        $firstLine = true;
        while (($row = fgetcsv($csvFile, 2000, ';')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            // check if business area exists
            $business_area = BusinessArea::where('business_area', $row[1])->first();
            if (!$business_area) {
                $business_area = new BusinessArea();

                // get last id
                $last_id = BusinessArea::orderBy('id', 'desc')->first();
                if ($last_id) {
                    $business_area->id = $last_id->id + 1;
                } else {
                    $business_area->id = 1;
                }
            }

            $business_area->company_code = $row[0];
            $business_area->business_area = $row[1];
            $business_area->description = $row[2];
            $business_area->status = 'ACTV';
            $business_area->save();
        }
        fclose($csvFile);
    }
}
