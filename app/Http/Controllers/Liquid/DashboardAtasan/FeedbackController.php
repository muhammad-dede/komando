<?php

namespace App\Http\Controllers\Liquid\DashboardAtasan;

use App\Http\Controllers\Controller;
use App\Services\LiquidService;
use App\Helpers\ConfigLabelHelper;
use App\Enum\ConfigLabelEnum;
use App\Utils\BasicUtil;
use Carbon\Carbon;
use DB;

class FeedbackController extends Controller
{
    public function index()
    {
        $params = (new BasicUtil)->getParams(request());

        $dataChart = app(LiquidService::class)->getDashboardAtasanDataChart(auth()->user(), $params);

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

        $btnActive = 'feedback';
        $label = new ConfigLabelHelper;
        $kelebihan = $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN);
        $kekurangan = $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN);
        $saran = $label->getLabel(ConfigLabelEnum::KEY_SARAN);

        return view(
            'liquid.dashboard-atasan.feedback',
            compact(
                'btnActive',
                'dataChart',
                'kelebihan',
                'kekurangan',
                'saran',
                'years',
                'yearNow'
            )
        );
    }
}
