<?php

namespace App\Http\Controllers\Liquid\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use App\Utils\BusinessAreaUtil;
use App\Utils\CompanyCodeUtil;
use App\Utils\UnitKerjaUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LiquidAllController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $unitCode = request()->get('unit_code', $user->business_area);
        $status = request()->get('status');
        $year = request()->get('year');

        $companyCode = (new UnitKerjaUtil)->shiftingBusinessArea($user);
        $coCodeList = (new BusinessAreaUtil)->generateOptions($user, $companyCode);

        $forUnit = ! empty($unitCode)
            ? $unitCode
            : $companyCode;

        $liquids = Liquid::forUnit($forUnit)
            ->when($year && $year !== 'Filter Periode', function ($query) use ($year) {
                return $query->whereRaw("EXTRACT(YEAR FROM FEEDBACK_START_DATE) = '$year'")
                    ->orWhereRaw("EXTRACT(YEAR FROM CREATED_AT) = '$year'");
            })
            ->get();

        if ($status) {
            $liquids = $liquids->filter(function ($liquid) use ($status) {
                return $status === $liquid->getCurrentSchedule();
            });
        }

        if ($unitCode) {
            $liquids->load('businessAreas');

            $liquids = $liquids->filter(function ($liquid) use ($unitCode) {
                return $liquid->businessAreas->where('business_area', $unitCode)->count() > 0;
            });
        }

        $unitCode = empty($unitCode) ? null : $unitCode;
        $years = collect(DB::select('SELECT EXTRACT(YEAR FROM FEEDBACK_START_DATE) AS YEAR FROM LIQUIDS GROUP BY EXTRACT(YEAR FROM FEEDBACK_START_DATE)'))
            ->each(function ($item) use ($year) {
                $isSelected = false;

                if ($year) {
                    $isSelected = $item->year === $year;
                }

                $item->isSelected = $isSelected;

                return $item;
            });
        $yearNow = Carbon::now()->format('Y');

        return view('liquid.dashboard-admin.all-liquid', compact(
            'liquids', 'unitCode', 'user', 'coCodeList',
            'years',
            'yearNow'
        ));
    }
}
