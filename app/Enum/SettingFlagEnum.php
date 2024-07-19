<?php

namespace App\Enum;

class SettingFlagEnum
{
    const ADMIN_ROOT = 'manual-book-admin-root';
    const ADMIN_UNIT_PELAKSANA = 'manual-book-admin-unit-pelaksana';
    const DASHBOARD_ATASAN = 'manual-book-dashboard-atasan';
    const DASHBOARD_BAWAHAN = 'manual-book-dashboard-bawahan';
    const FAQ = 'faq';

    public static function getAllKey()
    {
        return [
            self::ADMIN_ROOT,
            self::ADMIN_UNIT_PELAKSANA,
            self::DASHBOARD_ATASAN,
            self::DASHBOARD_BAWAHAN,
            self::FAQ,
        ];
    }
}