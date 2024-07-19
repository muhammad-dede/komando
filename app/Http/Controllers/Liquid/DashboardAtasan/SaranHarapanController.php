<?php

namespace App\Http\Controllers\Liquid\DashboardAtasan;

use App\Http\Controllers\Controller;
use App\Models\Liquid\Liquid;
use App\Services\LiquidService;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;
use App\Utils\BasicUtil;
use Carbon\Carbon;
use DB;

class SaranHarapanController extends Controller
{
    public function index()
    {
        $params = (new BasicUtil)->getParams(request());

        $liquid = Liquid::query()->published()->forYear($params->year)->forAtasan(auth()->user())->first(); // currentYear() tidak digunakan karena menggunakan filter tahun

        // Ditutup karena di dalamnya sudah ada filter berdasarkan tahun.
        // if (!$liquid) {
        //     return redirect('/')->with('warning', 'Saat ini belum ada Liquid aktif');
        // }

        if ($liquid instanceof Liquid) {
            $feedbacks = app(LiquidService::class)
                ->getLiquidWithFeedbacksAtasan($liquid);
        }

        $btnActive = 'saran-harapan';

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
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $saran = $label->getLabel(ConfigLabelEnum::KEY_SARAN);

        return view(
            'liquid.dashboard-atasan.saran-harapan',
            compact(
                'btnActive',
                'liquid',
                'feedbacks',
                'kelebihan',
                'kekurangan',
                'saran',
                'years',
                'yearNow'
            )
        );
    }
}
