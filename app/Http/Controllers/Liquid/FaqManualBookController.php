<?php

namespace App\Http\Controllers\Liquid;

use App\Http\Controllers\Controller;
use App\Models\Liquid\Settings;

use Illuminate\Http\Request;
use App\Enum\SettingFlagEnum;

class FaqManualBookController extends Controller
{
    public function edit()
    {
        $enum = new SettingFlagEnum;
        $faq = Settings::where('key', $enum::FAQ)->value('value');
        $manual_book_root = Settings::where('key', $enum::ADMIN_ROOT)->value('value');
        $manual_book_unit = Settings::where('key', $enum::ADMIN_UNIT_PELAKSANA)->value('value');
        $manual_book_atasan = Settings::where('key', $enum::DASHBOARD_ATASAN)->value('value');
        $manual_book_bawahan = Settings::where('key', $enum::DASHBOARD_BAWAHAN)->value('value');
        return view('liquid.setting.faq-manual-book', compact('faq', 'manual_book_root', 'manual_book_unit', 'manual_book_atasan', 'manual_book_bawahan'));
    }

    public function update(Request $request)
    {
        $enum = new SettingFlagEnum;
        $settings = $request->only($enum::ADMIN_ROOT, $enum::ADMIN_UNIT_PELAKSANA, $enum::DASHBOARD_ATASAN, $enum::DASHBOARD_BAWAHAN, $enum::FAQ);
        foreach ($settings as $key => $value) {
            Settings::updateOrInsert(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('faq-manual-book.index')->withSuccess('Informasi berhasil diperbarui');
    }
}
