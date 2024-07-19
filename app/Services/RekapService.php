<?php

namespace App\Services;

use App\Models\Liquid\Liquid;
use App\Utils\BasicUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class RekapService
{
    public function view($type)
    {
        $config = (new BasicUtil)->getConfig();
        $nav = 'all-' . $type;
        $companyCode = request('company_code', auth()->user()->company_code);
        $unitCode = request('unit_code', auth()->user()->business_area);
        $periode = (int) request('periode', Carbon::now()->year);

        $liquids = Liquid::query()
            ->forYear($periode)
            ->forCompany($companyCode)
            ->forUnit($unitCode)
            ->get();

        $data = $this->getData($type, $liquids, request('jabatan'));

        $view = 'liquid.dashboard-admin.rekap-all-' . $type;

        return view($view, compact('nav', 'data', 'config'));
    }

    private function getData($type, Collection $liquids, $jabatan)
    {
        $name = 'rekap' . ucfirst($type) . '-all';

        if (config('app.isUsingCache')) {
            if (Cache::has($name)) {
                return Cache::get($name);
            }

            return Cache::remember($name, 60, function () use ($liquids, $jabatan, $type) {
                return $this->getDataSpecific($type, $liquids, $jabatan);
            });
        }

        return $this->getDataSpecific($type, $liquids, $jabatan);
    }

    private function getDataSpecific($type, $liquids, $jabatan)
    {
        if ($type === 'kelebihan') {
            $result = (new LiquidReportService)->rekapKelebihan($liquids, $jabatan);
        } else {
            $result = (new LiquidReportService)->rekapKekurangan($liquids, $jabatan);
        }

        return $result;
    }
}
