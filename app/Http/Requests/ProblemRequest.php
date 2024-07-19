<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProblemRequest extends Request
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
            'nama'=>'required',
            'nip'=>'required',
            'unit'=>'required',
            'username'=>'required',
            'email'=>'required|email',
            'company_code'=>'required',
            'server_id'=>'required',
            'grup_id'=>'required',
            'deskripsi'=>'required',
            'tgl_kejadian'=>'required|date',
            'foto'=>'file|max:1024|image',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama pegawai wajib diisi',
            'nip.required' => 'NIP pegawai wajib diisi',
            'unit.required' => 'Unit/Area wajib diisi',
            'username.required' => 'User ESS wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email salah',

            'company_code.required' => 'Company Code wajib diisi',
            'server_id.required' => 'Server wajib dipilih',
            'grup_id.required' => 'Kategori masalah wajib dipilih',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'tgl_kejadian.required' => 'Tanggal kejadian harus diisi',
            'tgl_kejadian.date' => 'Tanggal kejadian harus berupa tanggal',
            'foto.file' => 'File evidence harus berupa file',
            'foto.max' => 'Ukuran file evidence maksimal 1MB',
            'foto.image' => 'Format file evidence harus berupa gambar (JPG)',
        ];
    }
}
