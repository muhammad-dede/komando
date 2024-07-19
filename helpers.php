<?php

use Carbon\Carbon;

if (!function_exists('app_parse_date')) {
    /**
     * Parse Date string (usually from form input) into Carbon  object
     *
     * @param string $path
     *
     * @return string
     */
    function app_parse_date($date, $format = null)
    {
        if (!$format) {
            $format = 'd-m-Y';
        }

        try {
            return \Carbon\Carbon::createFromFormat($format, $date);
        } catch (InvalidArgumentException $exception) {
            return null;
        }
    }
}

if (!function_exists('app_user_avatar')) {
    /**
     * Generate user photo URL
     *
     * @param string $nip
     *
     * @return string
     */
    function app_user_avatar($nip)
    {
        $foto = \App\User::where('nip', $nip)->value('foto');
        $path = 'assets/images/users/foto/'.$foto;
        if (is_file(public_path($path))) {
            return asset($path);
        }

        return asset('assets/images/user.jpg');
    }
}

if (!function_exists('app_format_skor_penilaian')) {
    /**
     * Format skor penilaian
     *
     * @param string $skor
     *
     * @return string
     */
    function app_format_skor_penilaian($skor)
    {
        return number_format($skor, 2);
    }
}

if (!function_exists('app_format_kelompok_jabatan')) {
    /**
     * Translasi nama kelompok jabatan dari const ke human friendly name
     *
     * @param string $jabatan Enum kelompok jabatan
     *
     * @return string
     */
    function app_format_kelompok_jabatan($jabatan)
    {
        return trans(sprintf('enum.%s.%s', \App\Enum\LiquidJabatan::class, $jabatan ?: 'uncategorized'));
    }
}

if (!function_exists('app_media_thumbnail')) {
    /**
     * Generate tag HTML untuk model Media sesuai tipenya (gambar atau video)
     *
     * @param \Spatie\MediaLibrary\Media $media
     *
     * @return string
     */
    function app_media_thumbnail($media)
    {
        if (!$media instanceof \Spatie\MediaLibrary\Media) {
            return null;
        }

        $type = $media->getTypeFromMimeAttribute();
        switch ($type) {
            case \Spatie\MediaLibrary\Media::TYPE_IMAGE:
                return sprintf('<div class="image" href="'.$media->getUrl().'"><img src="%s" class="img-responsive img-responsive img-fluid" alt=""></div>',
                    $media->getUrl());
                break;
            case \Spatie\MediaLibrary\Media::TYPE_OTHER:
                return sprintf('<video width="320" height="240" controls><source src="%s" type="video/mp4"></video>',
                    $media->getUrl());
                break;
            default:
                return "thumbnail not available";
        }
    }
}

if (!function_exists('app_nilai_to_range')) {
    /**
     * Ubah nilai 1 - 10 menjadi range penilaian
     *
     * @param int $nilai
     *
     * @return string
     */
    function app_nilai_to_range($nilai)
    {
        $map = [
            1 => \App\Enum\RangeNilaiPengukuran::SANGAT_JARANG,
            2 => \App\Enum\RangeNilaiPengukuran::SANGAT_JARANG,
            3 => \App\Enum\RangeNilaiPengukuran::JARANG,
            4 => \App\Enum\RangeNilaiPengukuran::JARANG,
            5 => \App\Enum\RangeNilaiPengukuran::KADANG_KADANG,
            6 => \App\Enum\RangeNilaiPengukuran::KADANG_KADANG,
            7 => \App\Enum\RangeNilaiPengukuran::SERING,
            8 => \App\Enum\RangeNilaiPengukuran::SERING,
            9 => \App\Enum\RangeNilaiPengukuran::SANGAT_SERING,
            10 => \App\Enum\RangeNilaiPengukuran::SANGAT_SERING,
        ];

        $default = \App\Enum\RangeNilaiPengukuran::KADANG_KADANG;

        return array_get($map, $nilai, $default);
    }
}

if (!function_exists('is_unit_pusat')) {
    /**
     * Cek apakah sebuah unit code itu pusat atau tidak
     *
     * @param string $unit
     *
     * @return bool
     */
    function is_unit_pusat($unit)
    {
        return $unit === '1001';
    }
}

function innerHTML(DOMElement $element)
{
    $doc = $element->ownerDocument;
    $html = '';

    foreach ($element->childNodes as $node) {
        $html .= $doc->saveHTML($node);
    }

    $html = html_entity_decode(urldecode($html));

    return $html;
}

if (! function_exists('set_liquid_bawahan_log_label'))
{
    function set_liquid_bawahan_log_label($title, \App\Models\Liquid\LiquidPeserta $peserta)
    {
        return sprintf(
            '[LIQUID] %s: %s %s',
            $title,
            $peserta->snapshot_nama_atasan,
            $peserta->atasan->nip
        );
    }
}

if (! function_exists('date_id'))
{
    function date_id(Carbon $carbon)
    {
        Carbon::setLocale('id');

        return $carbon->format('d F Y');
    }
}

if (! function_exists('datepicker_id'))
{
    function datepicker_id(Carbon $carbon = null)
    {
        if (empty($carbon)) {
            return null;
        }

        return $carbon->format('d-m-Y');
    }
}
