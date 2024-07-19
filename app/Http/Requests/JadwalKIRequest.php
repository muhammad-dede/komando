<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class JadwalKIRequest extends Request
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
            'tema_id_unit'=>'required',
            'judul_coc'=>'required',
//            'jenis_coc_id'=>'required',
            'pernr_leader'=>'required',
            'pedoman_perilaku_id'=>'required',
            'lokasi'=>'required',
            'tanggal_coc'=>'required|date',
            'jam'=>'required',
            'jml_peserta'=>'required',

            'judul_materi'=>'required',
            'deskripsi'=>'required',
            'pernr_penulis'=>'required',
            'materi'=>'file|max:1024|mimes:pdf',

        ];
    }

    public function messages()
    {
        return [
            'tema_id_unit.required' => 'Tema wajib dipilih',
            'judul_coc.required' => 'Judul CoC wajib diisi',
//            'jenis_coc_id.required' => 'Level CoC wajib dipilih',
            'pernr_leader.required' => 'CoC Leader wajib dipilih',
            'pedoman_perilaku_id.required' => 'DOs & DON\'Ts pedoman perilaku wajib dipilih',
            'lokasi.required' => 'Lokasi CoC wajib diisi',
            'tanggal_coc.required' => 'Tanggal CoC wajib diisi',
            'tanggal_coc.date' => 'Tanggal CoC harus berupa tanggal',
            'jam.required' => 'Jam CoC wajib diisi',
            'jml_peserta.required' => 'Jumlah peserta wajib diisi',

            'judul_materi.required' => 'Judul Materi wajib diisi',
            'deskripsi.required' => 'Isi materi tidak boleh kosong',
            'pernr_penulis.required' => 'Penulis wajib dipilih',
            'materi.file' => 'File materi harus berupa file',
            'materi.max' => 'Ukuran file materi maksimal 1MB',
            'materi.mimes' => 'Format file materi harus berupa PDF',
        ];
    }
}
