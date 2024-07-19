<?php

namespace App\Http\Requests\Liquid\Penyelarasan;

use App\Models\Liquid\Penyelarasan;
use App\Services\PenyelarasanService;

class Update extends Store
{
    public function rules()
    {
        $rules = [
            'date_start' => 'required',
            'date_end' => 'required|date_greater_or_sameass:date_start',
            'tempat_kegiatan' => 'required',
        ];

        $liquid = Penyelarasan::findOrFail($this->route('penyelarasan'))->liquid;
        $feedbackData = app(PenyelarasanService::class)->feedbackData($liquid, auth()->user());

        //TODO ganti angka 2 dengan data asli sesuai jumlah resolusi
        for ($i = 0; $i < count($feedbackData['kk_details']); $i++) {
            $rules['aksi_nyata_'.($i + 1)] = 'required';
        }

        return $rules;
    }
}
