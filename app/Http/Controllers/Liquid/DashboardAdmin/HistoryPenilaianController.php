<?php

namespace App\Http\Controllers\Liquid\DashboardAdmin;

use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Services\LiquidPesertaService;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;

class HistoryPenilaianController extends Controller
{
    public function show($liquidId, $userPERNR)
    {
        $liquid = Liquid::findOrFail($liquidId);
        $peserta = LiquidPeserta::where('atasan_id', $userPERNR)
            ->where('liquid_id', $liquid->id)
            ->firstOrFail();

        $atasanActivityLogBook = app(\App\Services\ActivityLog::class)
            ->getActiveLogsForAtasanPeserta($peserta->atasan_id);

        $detail = app(LiquidPesertaService::class)->getDetailForAtasan($peserta);
        $barChart = LiquidPesertaService::convertSpiderToBar($detail['resolusi']);
        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $saran = $label->getLabel(ConfigLabelEnum::KEY_SARAN);

        return view('liquid.dashboard-admin.detail-history-penilaian', compact(
            'detail', 'atasanActivityLogBook', 'kelebihan', 'kekurangan', 'saran', 'barChart'
        ));
    }
}
