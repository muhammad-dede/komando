<?php

namespace App\Policies\Liquid;

use App\Models\Liquid\Liquid;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLogBookPolicy
{
    use HandlesAuthorization;

    public function input(User $user, Liquid $liquid)
    {
        if (config('liquid.disable_date_validation')) {
            return true;
        }

        return Carbon::today()->timestamp > $liquid->pengukuran_pertama_end_date->timestamp;
    }

    public function canAccess(User $user)
    {
        return Liquid::forAtasan($user)->published()->exists(); //currentYear()
    }
}
