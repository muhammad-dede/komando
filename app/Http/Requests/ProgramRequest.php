<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProgramRequest extends Request
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
            'nama_kegiatan'=>'required',
            'deskripsi'=>'required',
            'kriteria_peserta'=>'required',
            'jenis_waktu_evp_id'=>'required',
            'kuota'=>'required|numeric',
            'tempat'=>'required',
            'waktu_awal'=>'required|date',
            'waktu_akhir'=>'required|date',
            'tgl_registrasi_awal'=>'required|date',
            'tgl_registrasi_akhir'=>'required|date',
            'tgl_pengumuman'=>'required|date',
            'foto'=>'file|max:1024|image',
            'dokumen'=>'file|max:5000',
            'data_diri'=>'file|max:5000',
            'surat_pernyataan'=>'file|max:5000',
            'company_code'=>'required',
            'business_area'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'nama_kegiatan.required'=>'Nama kegiatan wajib diisi',
            'deskripsi.required'=>'Deskripsi kegiatan wajib diisi',
            'kriteria_peserta.required'=>'Kriteria peserta wajib diisi',
            'jenis_waktu_evp_id.required'=>'Jenis waktu wajib dipilih',
            'kuota.required'=>'Kuota wajib diisi',
            'kuota.numeric'=>'Kuota harus berupa angka',
            'tempat.required'=>'Lokasi EVP wajib diisi',
            'waktu_awal.required'=>'Tanggal kegiatan awal wajib diisi',
            'waktu_awal.date'=>'Tanggal kegiatan awal harus berupa tanggal',
            'waktu_akhir.required'=>'Tanggal kegiatan akhir wajib diisi',
            'waktu_akhir.date'=>'Tanggal kegiatan awal harus berupa tanggal',
            'tgl_registrasi_awal.required'=>'Tanggal registrasi awal wajib diisi',
            'tgl_registrasi_awal.date'=>'Tanggal registrasi awal harus berupa tanggal',
            'tgl_registrasi_akhir.required'=>'Tanggal registrasi akhir wajib diisi',
            'tgl_registrasi_akhir.date'=>'Tanggal registrasi akhir harus berupa tanggal',
            'tgl_pengumuman.required'=>'Tanggal pengumuman wajib diisi',
            'tgl_pengumuman.date'=>'Tanggal pengumuman harus berupa tanggal',
            'foto.file'=>'Banner/Foto harus berupa file',
            'foto.max'=>'Ukuran file Banner/Foto maksimal 1MB',
            'foto.image'=>'Banner/Foto harus berupa gambar (JPG, JPEG, PNG)',
            'dokumen.file'=>'Surat/Dokumen harus berupa file',
            'dokumen.max'=>'Ukuran file Surat/Dokumen maksimal 5MB',
            'data_diri.file'=>'Form Data Diri harus berupa file',
            'data_diri.max'=>'Ukuran file Form Data Diri maksimal 5MB',
            'surat_pernyataan.file'=>'Surat Pernyataan harus berupa file',
            'surat_pernyataan.max'=>'Ukuran file Surat Pernyataan maksimal 5MB',
            'company_code.required'=>'Company Code wajib dipilih',
            'business_area.required'=>'Business Area wajib dipilih',
        ];
    }
}
