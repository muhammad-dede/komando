<?php

namespace App\Policies\Liquid;

use App\Models\Liquid\Liquid;
use App\Models\Liquid\LiquidPeserta;
use App\Models\Liquid\Penyelarasan;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenyelarasanPolicy
{
    use HandlesAuthorization;

    public function accessPenyelarasan(User $user)
    {
        return LiquidPeserta::where('atasan_id', $user->strukturJabatan->pernr)
            ->exists();
    }

    public function inputPenyelarasan(User $user, Liquid $liquid)
    {
        return Penyelarasan::where('atasan_id', $user->strukturJabatan->pernr)
            ->where('liquid_id', $liquid->id)
            ->exists() === false;
    }

    public function dateInProgress(User $user, Liquid $liquid)
    {
        if (!config('liquid.disable_date_validation')) {
            return strtotime(Carbon::today()) >= strtotime($liquid->penyelarasan_start_date)
                && strtotime(Carbon::today()) <= strtotime($liquid->penyelarasan_end_date->endOfDay());
        }

        return true;
    }
}
