<?php

namespace App\Enum;

class LiquidStatus
{
    const DRAFT = 'DRAFT'; //status on DB
    const PUBLISHED = 'PUBLISHED'; //status on DB
    const PUBLISHED_STATUS = 'Publish'; //labeling
    const DRAFT_STATUS = 'Draft'; //labeling
    const FEEDBACK_BERLANGSUNG = 'Feedback'; //labeling
    const PENYELARASAN = 'Penyelarasan'; //labeling
    const PENGUKURAN_PERTAMA = 'Pengukuran Pertama'; //labeling
    const PENGUKURAN_KEDUA = 'Pengukuran Kedua'; //labeling
    const SELESAI = 'Selesai'; //labeling

    public static function toDropdownArray()
    {
        return [
            static::PUBLISHED_STATUS,
            static::DRAFT_STATUS,
            static::FEEDBACK_BERLANGSUNG,
            static::PENYELARASAN,
            static::PENGUKURAN_PERTAMA,
            static::PENGUKURAN_KEDUA,
            static::SELESAI
        ];
    }

    public static function trackingWording($status, $num)
    {
        if ($status !== static::FEEDBACK_BERLANGSUNG
            && $status !== static::SELESAI
            && $status !== static::DRAFT_STATUS
            && $status !== static::PUBLISHED_STATUS) {
            $arr = static::toDropdownArray();

            unset(
                $arr[static::PUBLISHED_STATUS],
                $arr[static::DRAFT_STATUS]
            );

            $after = $arr[
                array_search($status, $arr) - 1
            ];

            return sprintf(
                "%s dilaksanakan oleh %u peserta penilaian, setelah %s selesai.",
                $status,
                $num,
                $after
            );
        } elseif ($status === static::FEEDBACK_BERLANGSUNG) {
            return sprintf(
                "%s dilaksanakan oleh %u peserta penilaian",
                $status,
                $num
            );
        } elseif ($status === static::PUBLISHED_STATUS) {
            return static::PUBLISHED_STATUS;
        } else {
            return "Liquid telah selesai dilakukan.";
        }
    }
}
