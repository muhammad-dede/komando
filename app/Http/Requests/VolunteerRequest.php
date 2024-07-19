<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class VolunteerRequest extends Request
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
            'answer_tertarik'=>'required|min:30',
            'answer_tepat'=>'required|min:30',
            'file_cv'=>'required|max:3000',
            'file_surat_pernyataan'=>'required|max:3000',
            'file_surat_ijin_keluarga'=>'required|max:3000',
            'file_surat_sehat'=>'required|max:3000',

        ];
    }

    public function messages()
    {
        return [
            'answer_tertarik.required'=>'Jawaban tidak boleh kosong',
            'answer_tertarik.min'=>'Jawaban minimal 30 karakter',
            'answer_tepat.required'=>'Jawaban tidak boleh kosong',
            'answer_tepat.min'=>'Jawaban minimal 30 karakter',
            'file_cv.required'=>'File Data diri harus dilampirkan',
            'file_cv.max'=>'Ukuran file Data diri tidak boleh melebihi 3 MB',
            'file_surat_pernyataan.required'=>'File Surat Pernyataan harus dilampirkan',
            'file_surat_pernyataan.max'=>'Ukuran file Surat Pernyataan tidak boleh melebihi 3 MB',
            'file_surat_ijin_keluarga.required'=>'File Surat ijin keluarta harus dilampirkan',
            'file_surat_ijin_keluarga.max'=>'Ukuran file Surat ijin keluarga tidak boleh melebihi 3 MB',
            'file_surat_sehat.required'=>'File Surat keterangan sehat harus dilampirkan',
            'file_surat_sehat.max'=>'Ukuran file Surat keterangan sehat tidak boleh melebihi 3 MB',
        ];
    }
}
