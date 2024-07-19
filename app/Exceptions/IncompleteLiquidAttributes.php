<?php

namespace App\Exceptions;

class IncompleteLiquidAttributes extends \DomainException
{
    const TANGGAL_BELUM_DIISI = 0;

    const UNIT_KERJA_BELUM_DIISI = 1;

    const PESERTA_BELUM_DIISI = 2;

    const DOKUMEN_BELUM_DIISI = 3;

    const UNIT_SUDAH_PUNYA_LIQUID = 4;
}
