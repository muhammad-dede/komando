<?php

namespace App\Http\Requests\Liquid\Penyelarasan;

use App\Http\Requests\Request;
use App\Models\Liquid\Liquid;
use App\Services\PenyelarasanService;

class Store extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'date_start' => 'required',
            'tempat_kegiatan' => 'required',
        ];

        $liquid = Liquid::findOrFail($this->input('liquid_id'));
        $feedbackData = app(PenyelarasanService::class)->feedbackData($liquid, auth()->user());

        //TODO ganti angka 2 dengan data asli sesuai jumlah resolusi
        for ($i = 0; $i < count($feedbackData['kk_details']); $i++) {
            $rules['aksi_nyata_'.($i + 1)] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'date_end.date_greater_or_sameass' => 'Periode tanggal pelaksanaan tidak valid',
            'aksi_nyata_1.required' => 'Aksi nyata untuk resolusi 1 harus diisi',
            'aksi_nyata_2.required' => 'Aksi nyata untuk resolusi 2 harus diisi',
            'aksi_nyata_3.required' => 'Aksi nyata untuk resolusi 3 harus diisi',
        ];
    }
}
