<?php

namespace App\Http\Controllers\Liquid\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use App\Services\LiquidService;
use App\Utils\BasicUtil;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;
use Carbon\Carbon;
use DB;

class StatusLiquidController extends Controller
{
    // public function index()
    // {
    //     $user = auth()->user();
    //     $params = (new BasicUtil)->getParams(request());

    //     $liquid = Liquid::query()->activeForUnit(request('unit_code', auth()->user()->business_area))->first();

    //     if ($liquid instanceof Liquid) {
    //         $jadwalStatus = app(LiquidService::class)
    //             ->getJadwalProgressStatus($liquid);
    //     }

    //     $btnActive = 'status-liquid';

    //     $label = new ConfigLabelHelper;
    //     $usulan_atasan = $label->getLabel(ConfigLabelEnum::KEY_USULAN_ATASAN);

    //     return view('liquid.dashboard-admin.status-liquid', compact(
    //         'btnActive',
    //         'liquid',
    //         'jadwalStatus',
    //         'user',
    //         'params',
    //         'usulan_atasan'
    //     ));
    // }

    // mew method with year filter
    public function index()
    {
        $user = auth()->user();
        $params = (new BasicUtil)->getParams(request());

        $liquid = Liquid::query()->activeForUnit(request('unit_code', auth()->user()->business_area))->forYear($params->year)->first();
        
        if ($liquid instanceof Liquid) {
            $jadwalStatus = app(LiquidService::class)
                ->getJadwalProgressStatus($liquid);
        }

        $btnActive = 'status-liquid';

        $yearNow = Carbon::now()->format('Y');
        $years = collect(DB::select('SELECT EXTRACT(YEAR FROM FEEDBACK_START_DATE) AS YEAR FROM LIQUIDS GROUP BY EXTRACT(YEAR FROM FEEDBACK_START_DATE)'))
            ->each(function($item) use ($params) {
                $isSelected = false;

                if ($params->year) {
                    $isSelected = $item->year === $params->year;
                }

                $item->isSelected = $isSelected;

                return $item;
            });

        $label = new ConfigLabelHelper;
        $usulan_atasan = $label->getLabel(ConfigLabelEnum::KEY_USULAN_ATASAN);

        return view('liquid.dashboard-admin.status-liquid', compact(
            'btnActive',
            'liquid',
            'jadwalStatus',
            'user',
            'params',
            'years',
            'yearNow',
            'usulan_atasan'
        ));
    }
}
