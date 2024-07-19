<?php

namespace App\Policies\Liquid;

use App\Models\Liquid\LiquidPeserta;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PengukuranPertamaPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function hasPenyelarasan(User $user, $idLiquidPeserta)
    {
        $liquidPeserta = LiquidPeserta::find($idLiquidPeserta);
        $atasanPernr = $liquidPeserta
            ->atasan_id;

        return $liquidPeserta->liquid
            ->penyelarasan()
            ->where('atasan_id', $atasanPernr)
            ->exists();
    }
}
