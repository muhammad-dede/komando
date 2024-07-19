<?php

namespace App\Http\Controllers\Liquid\DashboardAtasan;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Services\LiquidService;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;

class LiquidController extends Controller
{
    public function index()
    {
        $jadwalLiquid = app(LiquidService::class)->jadwalAtasan(auth()->user());
        $historiPenilaian = app(LiquidService::class)->getHistoryPenilaianAtasan(auth()->user());
        $btnActive = 'kalendar-liquid';

        Activity::log('[LIQUID] Akses Dashboard Atasan', 'success');

        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $saran = $label->getLabel(ConfigLabelEnum::KEY_SARAN);

        return view('liquid.dashboard-atasan.kalendar-liquid', compact('btnActive', 'jadwalLiquid', 'historiPenilaian', 'kelebihan', 'kekurangan', 'saran'));
    }
}
