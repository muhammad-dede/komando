<?php

namespace App\Utils;

use App\Coc;
use Illuminate\Support\Collection;

class CocUtil
{
    public function generateCallback($cc_selected, $date, $jenisCocId)
    {
        return function ($query) use ($cc_selected, $date, $jenisCocId) {
            $query->with([
                'cocOpen' => function ($query) use ($date, $jenisCocId) {
                    $query->where('jenis_coc_id', $jenisCocId)
                        ->whereDate('tanggal_jam', '>=', $date->start->format('Y-m-d'))
                        ->whereDate('tanggal_jam', '<=', $date->end->format('Y-m-d'));
                },
                'cocComp' => function ($query) use ($date, $jenisCocId) {
                    $query->where('jenis_coc_id', $jenisCocId)
                        ->whereDate('tanggal_jam', '>=', $date->start->format('Y-m-d'))
                        ->whereDate('tanggal_jam', '<=', $date->end->format('Y-m-d'));
                }
            ])->whereIn('company_code', $cc_selected);
        };
    }

    public function generateReport(Collection $users)
    {
        return $users->map(function ($item) {
            $total = 0;
            $totalBaca = 0;

            foreach ($item->cocComp as $coc) {
                $jml = $coc->attendants->count();
                $total = $total + $jml;

                if($coc->materi!=null) {
                    $jmlBaca = $coc->materi->getReaderFromAdmin($item->id)->unique('nip')->count();
                    $totalBaca = $totalBaca + $jmlBaca;
                }
            }

            $item['jml_checkin'] = $total;
            $item['jml_baca'] = $totalBaca;

            return $item;
        });
    }

    public function getSumRangeDateCompanyCode(array $companyCode, $status, $startedAt, $endedAt)
    {
        return Coc::whereIn('company_code', $companyCode)
            ->where('status', $status)
            ->whereDate('tanggal_jam', '>=', $startedAt)
            ->whereDate('tanggal_jam', '>=', $endedAt)
            ->count();
    }
}
