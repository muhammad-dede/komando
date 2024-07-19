<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CompleteRequest extends Request
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
            'nip_leader'=>'required',
            'tanggal_coc'=>'required',
            'jam_coc'=>'required',
            'jml_peserta_dispensasi'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'nip_leader.required' => 'Pembawa Materi / CoC Leader belum dipilih',
            'tanggal_coc.required' => 'Tanggal CoC wajib diisi',
            'jam_coc.required' => 'Jam CoC wajib diisi',
            'jml_peserta_dispensasi.required' => 'Jumlah peserta dispensiasi wajib diisi meskipun nol',
        ];
    }
}
