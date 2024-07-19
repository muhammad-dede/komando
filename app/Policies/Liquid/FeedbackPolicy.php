<?php

namespace App\Policies\Liquid;

use App\Enum\LiquidStatus;
use App\Models\Liquid\LiquidPeserta;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackPolicy
{
    use HandlesAuthorization;

    public function inputFeedback(User $user, LiquidPeserta $peserta)
    {
        $belumMengisiFeedback = !$peserta->feedback()->exists();

        if (config('liquid.disable_date_validation')) {
            return $belumMengisiFeedback;
        }

        return strtotime('now') >= strtotime(Carbon::parse($peserta->liquid->feedback_start_date))
            && strtotime('now') <= strtotime(Carbon::parse($peserta->liquid->feedback_end_date)->endOfDay())
            && $belumMengisiFeedback
            && (string) $user->strukturJabatan->pernr === $peserta->bawahan_id;
    }

    public function updateFeedback(User $user, LiquidPeserta $peserta)
    {
        $sudahMengisiFeedback = $peserta->feedback()->exists();

        if (config('liquid.disable_date_validation')) {
            return $sudahMengisiFeedback;
        }

        return strtotime('now') >= strtotime(Carbon::parse($peserta->liquid->feedback_start_date))
            && strtotime('now') <= strtotime(Carbon::parse($peserta->liquid->feedback_end_date)->endOfDay())
            && $sudahMengisiFeedback
            && (string) $user->strukturJabatan->pernr === $peserta->bawahan_id;
    }

    public function cantFeedback(User $user, LiquidPeserta $peserta)
    {
        return strtotime('now') < strtotime(Carbon::parse($peserta->liquid->feedback_start_date))
            || strtotime('now') > strtotime(Carbon::parse($peserta->liquid->feedback_end_date)->endOfDay());
    }

    public function pengukuranKedua(User $user, LiquidPeserta $peserta)
    {
        if ($peserta->force_pengukuran_kedua) {
            return true;
        }

        if ($peserta->liquid->getCurrentSchedule() === LiquidStatus::PENGUKURAN_KEDUA) {
            return $peserta->pengukuranKedua === null;
        }

        return false;
    }
}
