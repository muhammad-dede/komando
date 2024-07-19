<?php

namespace App\Http\Controllers\Liquid\DashboardBawahan;

use App\Http\Controllers\Controller;
use App\Models\Liquid\ActivityLogBook;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Services\LiquidService;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;

class ResolusiAtasanController extends Controller
{
    public function index()
    {
        $resAtasans = LiquidPeserta::where(
            'bawahan_id',
            auth()->user()->strukturJabatan->pernr
        )
            ->whereHas('liquid', function ($q) {
                $q->published()->currentYear()->forBawahan(auth()->user());
            })
            ->get()
            ->map(function ($item) {
                $data = [
                    'atasan' => $item->liquid
                        ->peserta_snapshot[$item->atasan_id],
                    'liquid' => $item->liquid,
                    'resolusi' => [],
                    'feedback' => $item->feedback,
                ];

                $data['resolusi'] = app(LiquidService::class)->resolusi($item->atasan_id, $item->liquid);

                return $data;
            });

        $btnActive = 'resolusi-atasan';

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
            'liquid.dashboard-bawahan.resolusi-atasan',
            compact(
                'btnActive',
                'resAtasans',
                'logs',
                'kelebihan',
                'kekurangan',
                'usulan_atasan'
            )
        );
    }
}
