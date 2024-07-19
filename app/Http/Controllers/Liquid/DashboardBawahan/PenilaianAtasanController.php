<?php

namespace App\Http\Controllers\Liquid\DashboardBawahan;

use App\BusinessArea;
use App\Http\Controllers\Controller;
use App\Models\Liquid\ActivityLogBook;
use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use Illuminate\Support\Facades\DB;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;

class PenilaianAtasanController extends Controller
{
    public function index()
    {
        $resAtasans = LiquidPeserta::where(
            'bawahan_id',
            auth()->user()->strukturJabatan->pernr
        )
            ->whereHas('liquid', function ($q) {
                $q->published()->whereYear('liquids.created_at', '=', date('Y'));
            })
            ->get()
            ->map(function ($item) {
                $key = $item->atasan_id;

                return [
                    'atasan' => $item->liquid
                        ->peserta_snapshot[$key],
                    'liquid' => $item->liquid,
                    'feedback' => $item->feedback,
                    'foto' => $item->atasan
                        ->user
                        ->foto,
                    'business_area' => BusinessArea::where(
                        'business_area',
                        DB::table('v_liquid_peserta_snapshot')
                            ->where('liquid_id', $item->liquid->id)
                            ->where('nip_bawahan', $item->bawahan->user->nip)
                            ->first()
                            ->business_area_atasan
                    )
                        ->first()
                ];
            });

        $btnActive = 'penilaian-atasan';

        $liquid = Liquid::query()->published()->currentYear()->forBawahan(auth()->user())->first();
        $logs = [];
        if ($liquid) {
            $atasanId = $liquid
                ->peserta()
                ->where(function ($q) {
                    $q->where('bawahan_id', auth()->user()->strukturJabatan->pernr);
                })
                ->get()
                ->map(function ($peserta) {
                    return $peserta->atasan->user->id;
                })->toArray();

            $logs = ActivityLogBook::where('liquid_id', $liquid->id)
                ->whereIn('created_by', $atasanId)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $saran = $label->getLabel(ConfigLabelEnum::KEY_SARAN);
        $usulan_atasan = $label->getLabel(ConfigLabelEnum::KEY_USULAN_ATASAN);

        return view(
            'liquid.dashboard-bawahan.penilaian-atasan',
            compact(
                'logs',
                'btnActive',
                'resAtasans',
                'kelebihan',
                'kekurangan',
                'saran',
                'usulan_atasan'
            )
        );
    }
}
