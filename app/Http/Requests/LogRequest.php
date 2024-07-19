<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class LogRequest extends Request
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
            'aktivitas'=>'required|min:30',
            'tanggal'=>'required|date',
            'jam'=>'required',
            'lokasi'=>'required',
            'foto_1'=>'required|file|max:3024|image',
            'foto_2'=>'file|max:3024|image',
            'foto_3'=>'file|max:3024|image',
        ];
    }

    public function messages()
    {
        return [
            'aktivitas.required'=>'Deskripsi kegiatan wajib diisi',
            'aktivitas.min'=>'Deskripsi kegiatan minimal 30 karakter',
            'tanggal.required'=>'Tanggal kegiatan wajib diisi',
            'tanggal.date'=>'Tanggal kegiatan harus berupa tanggal',
            'jam.required'=>'Jam kegiatan wajib diisi',
            'lokasi.required'=>'Lokasi wajib diisi',
            'foto_1.required'=>'Masukkan foto kegiatan',
            'foto_1.file'=>'Foto kegiatan harus berupa file',
            'foto_1.max'=>'Ukuran file Foto maksimal 3MB',
            'foto_1.image'=>'Foto kegiatan harus berupa gambar (JPG, JPEG, PNG)',
            'foto_2.file'=>'Foto kegiatan harus berupa file',
            'foto_2.max'=>'Ukuran file Foto maksimal 3MB',
            'foto_2.image'=>'Foto kegiatan harus berupa gambar (JPG, JPEG, PNG)',
            'foto_3.file'=>'Foto kegiatan harus berupa file',
            'foto_3.max'=>'Ukuran file Foto maksimal 3MB',
            'foto_3.image'=>'Foto kegiatan harus berupa gambar (JPG, JPEG, PNG)',

        ];
    }
}
