<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class JadwalRequest extends Request
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
            'judul'=>'required',
//            'jenis_coc_id'=>'required',
            'nip_pemateri'=>'required',
            // 'pedoman_perilaku_id'=>'required',
            'lokasi'=>'required',
            'jam'=>'required',
            'jml_peserta'=>'required',
            'pelanggaran'=>'required',

        ];
    }

    public function messages()
    {
        return [
            'judul.required' => 'Judul wajib diisi',
//            'jenis_coc_id.required' => 'Level CoC wajib dipilih',
            'nip_pemateri.required' => 'CoC Leader wajib dipilih',
            // 'pedoman_perilaku_id.required' => 'DOs & DON\'Ts pedoman perilaku wajib dipilih',
            'lokasi.required' => 'Lokasi CoC wajib diisi',
            'jam.required' => 'Jam CoC wajib diisi',
            'jml_peserta.required' => 'Jumlah peserta wajib diisi',
            'pelanggaran.required' => 'Pelanggaran disiplin wajib dipilih.',
        ];
    }
}
