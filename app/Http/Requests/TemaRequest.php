<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class TemaRequest extends Request
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
            'tema_id'=>'required',
            'start_date'=>'required|date',
            'end_date'=>'required|date',

        ];
    }

    public function messages()
    {
        return [
            'tema_id.required' => 'Tema wajib dipilih',
            'start_date.required' => 'Tanggal awal tema wajib diisi',
            'start_date.date' => 'Tanggal awal tema harus berupa tanggal',
            'end_date.required' => 'Tanggal akhir tema wajib diisi',
            'end_date.date' => 'Tanggal akhir tema harus berupa tanggal',
        ];
    }
}
