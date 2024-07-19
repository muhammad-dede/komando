<?php

namespace App\Services;

use App\Activity;
use App\UnitKerja;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UnitKerjaService
{
    public function store(Collection $businessArea, User $user)
    {
        DB::beginTransaction();

        try {
            $user->unitKerjas()->delete();

            $unitKerja = $businessArea->map(function ($item) use ($user) {
                return [
                    'user_id' => $user->id,
                    'm_business_area_id' => $item->id,
                    'business_area' => $item->business_area,
                    'company_code' => $item->company_code,
                ];
            })->toArray();

            UnitKerja::insert($unitKerja);

            DB::commit();

            return true;
        } catch (QueryException $th) {
            DB::rollback();

            $errorMessage = $th->getMessage();

            Activity::log("Update unit kerja error: $errorMessage", 'danger');

            return false;
        }
    }
}
