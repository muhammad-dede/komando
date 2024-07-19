<?php

namespace App\Http\Controllers\Liquid\DashboardAtasan;

use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Services\LiquidPesertaService;
use App\Services\LiquidService;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;
use App\Utils\BasicUtil;
use Carbon\Carbon;
use DB;

class HistoryPenilaianController extends Controller
{
    public function index()
    {
        $params = (new BasicUtil)->getParams(request());

        $resolusi = app(LiquidService::class)->resolusi(auth()->user()->strukturJabatan->pernr, $params);

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

        $btnActive = 'history-penilaian';
        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $saran = $label->getLabel(ConfigLabelEnum::KEY_SARAN);

        return view(
            'liquid.dashboard-atasan.history-penilaian',
            compact(
                'btnActive',
                'kelebihan',
                'kekurangan',
                'saran',
                'resolusi',
                'years',
                'yearNow'
            )
        );
    }

    public function show($id)
    {
        $userPERNR = auth()->user()->strukturJabatan->pernr;
        $liquid = Liquid::findOrFail($id);
        $peserta = LiquidPeserta::where('atasan_id', $userPERNR)->where('liquid_id', $liquid->id)->firstOrFail();

        $detail = app(LiquidPesertaService::class)->getDetailForAtasan($peserta);

        if ($liquid instanceof Liquid) {
            $jadwalStatus = app(LiquidService::class)
                ->getJadwalProgressStatus($liquid);
        }
        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $saran = $label->getLabel(ConfigLabelEnum::KEY_SARAN);

        return view(
            'liquid.dashboard-atasan.detail-history-penilaian',
            compact(
                'detail',
                'liquid',
                'jadwalStatus',
                'kelebihan',
                'kekurangan',
                'saran'
            )
        );
    }
}
