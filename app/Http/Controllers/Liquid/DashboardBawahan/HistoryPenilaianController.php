<?php

namespace App\Http\Controllers\Liquid\DashboardBawahan;

use App\Http\Controllers\Controller;
use App\Models\Liquid\LiquidPeserta;
use App\Services\LiquidPesertaService;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;

class HistoryPenilaianController extends Controller
{
    public function show($pesertaId)
    {
        $peserta = LiquidPeserta::findOrFail((int) $pesertaId);

        $detail = app(LiquidPesertaService::class)->getDetail($peserta);
        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $saran = $label->getLabel(ConfigLabelEnum::KEY_SARAN);
        $usulan_atasan = $label->getLabel(ConfigLabelEnum::KEY_USULAN_ATASAN);

        return view('liquid.dashboard-bawahan.detail-history-penilaian', compact('detail', 'kelebihan', 'kekurangan', 'saran', 'usulan_atasan'));
    }
}
