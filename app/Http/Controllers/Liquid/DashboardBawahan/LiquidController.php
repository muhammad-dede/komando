<?php

namespace App\Http\Controllers\Liquid\DashboardBawahan;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Services\LiquidService;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;

class LiquidController extends Controller
{
    public function index()
    {
        $jadwalLiquid = app(LiquidService::class)->jadwalBawahan(auth()->user());
        $btnActive = 'kalendar-liquid';

        Activity::log('[LIQUID] Akses Dashboard Bawahan', 'success');
        
        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $usulan_atasan = $label->getLabel(ConfigLabelEnum::KEY_USULAN_ATASAN);

        return view('liquid.dashboard-bawahan.kalendar-liquid', compact('btnActive', 'jadwalLiquid', 'kelebihan', 'kekurangan', 'usulan_atasan'));
    }
}
