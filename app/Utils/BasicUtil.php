<?php

namespace App\Utils;

use App\Enum\ConfigLabelEnum;
use App\Enum\RolesEnum;
use App\Helpers\ConfigLabelHelper;
use App\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BasicUtil
{
    public function getParams(Request $request)
    {
        return (object) [
            'unitCode' => $request->unit_code,
            'divisi' => $request->divisi,
            'year' => empty($request->year)
                ? Carbon::now()->format('Y')
                : $request->year,
            'date' => (object) [
                'start' => ! empty($request->start_date)
                    ? Carbon::parse($request->start_date)
                    : null,
                'end' => ! empty($request->end_date)
                    ? Carbon::parse($request->end_date)
                    : null,
            ],
        ];
    }

    public function getRolesArray()
    {
        return Role::whereNotIn('id',[1])
            ->orderBy('display_name', 'asc')
            ->pluck('display_name', 'id')
            ->toArray();
    }

    public function convertPengukurangToRange(array $data)
    {
        $result = [];
        $format = '<span class="badge badge-%s">%s</span>';

        foreach ($data as $key => $value) {
            $value = (int) $value;

            if ($value <= 2) {
                $result[$key] = sprintf($format, 'danger', $value);
            } elseif ($value > 2 && $value <= 4) {
                $result[$key] = sprintf($format, 'warning', $value);
            } elseif ($value > 4 && $value <= 6) {
                $result[$key] = sprintf($format, 'success', $value);
            } elseif ($value > 6 && $value <= 8) {
                $result[$key] = sprintf($format, 'primary bg-blue-2', $value);
            } else {
                $result[$key] = sprintf($format, 'primary', $value);
            }
        }

        return $result;
    }

    public function getConfig()
    {
        if (Cache::has('basicUtilConfig')) {
            return Cache::get('basicUtilConfig');
        }

        $ttl = 60;
        $label = new ConfigLabelHelper;

        return Cache::remember('basicUtilConfig', $ttl, function () use ($label) {
            return (object) [
                'lebih' => $label->getLabel(ConfigLabelEnum::KEY_KELEBIHAN),
                'lebihShort' => $label->getLabelSort(ConfigLabelEnum::KEY_KELEBIHAN),
                'kurang' => $label->getLabel(ConfigLabelEnum::KEY_KEKURANGAN),
                'kurangShort' => $label->getLabelSort(ConfigLabelEnum::KEY_KEKURANGAN),
            ];
        });
    }
}
