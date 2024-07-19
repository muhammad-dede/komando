<?php

namespace App\Http\Controllers\Liquid\DashboardBawahan;

use App\Http\Controllers\Controller;
use App\Models\Liquid\ActivityLogBook;
use App\Models\Liquid\Liquid;
use App\Services\LiquidService;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;

class StatusLiquidController extends Controller
{
    public function index()
    {
        $liquid = Liquid::query()->activeForUnit(request('unit_code', auth()->user()->business_area))->first();

        if ($liquid instanceof Liquid) {
            $jadwalStatus = app(LiquidService::class)
                ->getJadwalProgressStatus($liquid);
        }

        $btnActive = 'status-liquid';

        $liquid = Liquid::query()->published()->currentYear()->forBawahan(auth()->user())->first();
        $logs = [];
        if ($liquid) {
            $logs = ActivityLogBook::where('liquid_id', $liquid->id)->orderBy('created_at', 'desc')->get();
        }

        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $usulan_atasan = $label->getLabel(ConfigLabelEnum::KEY_USULAN_ATASAN);

        return view(
            'liquid.dashboard-bawahan.status-liquid',
            compact(
                'btnActive',
                'liquid',
                'jadwalStatus',
                'logs',
                'kelebihan',
                'kekurangan',
                'usulan_atasan'
            )
        );
    }
}
