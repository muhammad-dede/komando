<?php

namespace App\Http\Controllers\Liquid\DashboardAdmin;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Services\LiquidService;
use App\Utils\BasicUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;

class LiquidController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $params = (new BasicUtil)->getParams(request());

        $jadwalLiquid = app(LiquidService::class)->jadwal(
            request('unit_code', auth()->user()->business_area),
            $params
        );
        
        $btnActive = 'kalendar-liquid';

        Activity::log('[LIQUID] Akses Dashboard Admin', 'success');

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

        return view('liquid.dashboard-admin.kalendar-liquid', compact(
            'btnActive',
            'jadwalLiquid',
            'user',
            'params',
            'years',
            'yearNow',
            'usulan_atasan'
        ));
    }
}
