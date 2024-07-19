<?php

namespace App\Models\Traits;

use App\Enum\LiquidStatus;
use App\Models\Liquid\BusinessArea;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait LiquidHasScope
{
    public function scopePublished(Builder $query)
    {
        $query->where('status', LiquidStatus::PUBLISHED);
    }

    public function scopeCurrentYear(Builder $query)
    {
        $query->forYear(Carbon::now()->year);
    }

    public function scopeForYear(Builder $query, $year)
    {
        $query->whereYear('feedback_start_date', '=', $year);
    }

    public function scopeForUnit(Builder $query, $businessArea)
    {
        if ($businessArea) {
            $query->whereHas('businessAreas', function ($q) use ($businessArea) {
                if (is_array($businessArea)) {
                    return $q->whereIn('liquid_business_area.business_area', $businessArea);
                } else {
                    return $q->where('liquid_business_area.business_area', $businessArea);
                }
            });
        }
    }

    public function scopeForCompany(Builder $query, $companyCode)
    {
        if ($companyCode) {
            $query->whereHas('businessAreas', function ($q) use ($companyCode) {
                $areas = BusinessArea::where('company_code', $companyCode)->pluck('business_area');
                $q->whereIn('liquid_business_area.business_area', $areas);
            });
        }
    }

    public function scopeForUser(Builder $query, User $user)
    {
        $query->whereHas('peserta', function ($queryPeserta) use ($user) {
            $pernr = @$user->strukturJabatan->pernr;
            $queryPeserta->where('atasan_id', $pernr)->orWhere('bawahan_id', $pernr);
        });
    }

    public function scopeForPernr(Builder $query, $pernr)
    {
        $query->whereHas('peserta', function ($queryPeserta) use ($pernr) {
            $queryPeserta->where('atasan_id', $pernr)->orWhere('bawahan_id', $pernr);
        });
    }

    public function scopeForBawahan(Builder $query, User $user)
    {
        $query->whereHas('peserta', function ($queryPeserta) use ($user) {
            $pernr = $user->strukturJabatan->pernr;
            $queryPeserta->where('bawahan_id', $pernr);
        });
    }

    public function scopeForAtasan(Builder $query, User $user)
    {
        $query->whereHas('peserta', function ($queryPeserta) use ($user) {
            $pernr = $user->strukturJabatan->pernr;
            $queryPeserta->where('atasan_id', $pernr);
        });
    }

    public function scopeActiveForUnit(Builder $query, $businessArea, $params = null)
    {
        $query->published();//->currentYear();

        if ($businessArea) {
            $query->forUnit($businessArea);
        }

        if ($params) {
            $query->when($params->date->start && $params->date->end, function ($query) use ($params) {
                return $query->whereBetween('feedback_start_date', [$params->date->start, $params->date->end]);
            });

            $query->when($params->year, function ($query) use ($params) {
                $year = $params->year;

                return $query->whereRaw("EXTRACT(YEAR FROM FEEDBACK_START_DATE) = '$year'");
            });
        }
    }

    public function scopeActiveForUnitWhitoutCurrentYear(Builder $query, $businessArea)
    {
        $query->published()->forUnit($businessArea);
    }

    public function scopeActiveForUser(Builder $query, $user)
    {
        if ($user instanceof User) {
            $query->published()->currentYear()->forUser($user);
        } elseif (is_string($user)) {
            $query->published()->currentYear()->forPernr($user);
        }
    }

    public function scopeActiveForAtasan(Builder $query, User $user)
    {
        $query->published()->currentYear()->forAtasan($user);
    }
}
