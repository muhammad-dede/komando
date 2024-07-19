<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MateriRequest extends Request
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
            'deskripsi'=>'required',
            'tanggal_coc'=>'required|date',
//            'pernr_penulis'=>'required',
            'materi'=>'file|max:5120|mimes:pdf',
        ];
    }

    public function messages()
    {
        return [
            'judul.required' => 'Judul wajib diisi',
            'deskripsi.required' => 'Isi materi tidak boleh kosong',
            'tanggal_coc.required' => 'Tanggal CoC harus dipilih',
            'tanggal_coc.date' => 'Tanggal awal tema harus berupa tanggal',
//            'pernr_penulis.required' => 'Penulis materi wajib diisi',
            'materi.file' => 'File materi harus berupa file',
            'materi.max' => 'Ukuran file materi maksimal 5MB',
            'materi.mimes' => 'Format file materi harus berupa PDF',
        ];
    }
}
