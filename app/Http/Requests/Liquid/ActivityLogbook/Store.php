<?php

namespace App\Http\Requests\Liquid\ActivityLogbook;

use App\Http\Requests\Request;

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
        return [
            'nama_kegiatan' => ['required', 'string'],
            'start_date' => ['required'],
            'end_date' => ['required', 'date_greater_or_sameass:start_date'],
            'tempat_kegiatan' => ['required', 'string'],
            'deskripsi' => ['required', 'string'],
            'resolusi.*' => ['required'],
        ];
    }

    public function attributes()
    {
        return [
            'nama_kegiatan' => 'Nama Kegiatan',
            'start_date' => 'Tanggal Mulai Pelaksanaan',
            'end_date' => 'Tanggal Selesai Pelaksanaan',
            'tempat_kegiatan' => 'Tempat Kegiatan',
            'deskripsi' => 'Deskripsi',
            'resolusi.*' => 'Resolusi',
        ];
    }

    public function messages()
    {
        return [
          'end_date.date_greater_or_sameass' => ':attribute harus setelah atau sama dengan Tanggal Mulai Pelaksanaan'
        ];
    }
}
