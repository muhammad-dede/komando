<?php

namespace App\Services;

use App\Models\Liquid\ActivityLogBook;
use App\Models\Liquid\Liquid;
use App\User;

class ActivityLog
{
    public function getActiveLogsForAtasan(User $user)
    {
        $activeLiquids = Liquid::query()->activeForAtasan($user)->get();

        return $this->getActivityLogBook($activeLiquids, $user);
    }

    public function getActiveLogsForAtasanPeserta($pernr)
    {
        $user = User::whereHas('strukturJabatan', function ($q) use ($pernr) {
            $q->where('pernr', $pernr);
        })->first();

        $activeLiquids = Liquid::query()
            ->forPernr($pernr)
            ->get();

        if ($user instanceof User) {
            return $this->getActivityLogBook($activeLiquids, $user);
        }

        return collect([]);
    }

    public function getActivityLogBook($activeLiquids, User $user)
    {
        $logs = [];
        if (!$activeLiquids->isEmpty()) {
            $logs = ActivityLogBook::where('created_by', $user->id)
                ->whereIn('liquid_id', $activeLiquids->pluck('id'))
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return $logs;
    }
}
