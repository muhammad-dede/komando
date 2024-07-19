<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'manajemen-media-banner/*',
        'manajemen-media-banner',
        'api/check-absence',
        'api/check-absence-1-level',
        'api/get-organisasi',
        'api/get-data-organisasi-pegawai',
        'api/get-coc-nasional',
        'api/get-children-organisasi',
        'api/get-data-organisasi-pegawai-1-level',
        'telebot/webhook/bot/1845084070:AAFpp46au_1ttBU4JVI48IPTv9EfLf0fWZg',
        'api/update-replied-bot-log',
    ];
}
