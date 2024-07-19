<?php

namespace App\Policies\Liquid;

use App\Models\Liquid\Liquid;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class LiquidPesertaPolicy
{
    use HandlesAuthorization;

    public function pesertaExist(User $user, Liquid $liquid, Request $request)
    {
        return !$liquid
            ->peserta()
            ->where('atasan_id', $request->get('atasan_id'))
            ->whereIn('bawahan_id', $request->input('bawahan', []))
            ->exists();
    }

    public function atasanExist(User $user, Liquid $liquid, Request $request)
    {
        return !$liquid
            ->peserta()
            ->where('atasan_id', $request->get('atasan_id'))
            ->exists();
    }
}
