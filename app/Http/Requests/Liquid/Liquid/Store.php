<?php

namespace App\Http\Requests\Liquid\Liquid;

use App\Http\Requests\Request;
use App\Models\Liquid\KelebihanKekurangan;

class Store extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $rules = [
            'reminder_aksi_resolusi' => ['required'],
            'kelebihan_kekurangan_id' => ['required'],
        ];

        if (!config('liquid.disable_date_validation')) {
            $rules = array_merge($rules, [
                'feedback_start_date' => ['required', 'after:today'],
                'feedback_end_date' => ['required', 'date_greater_or_sameass:feedback_start_date'],
                'penyelarasan_start_date' => ['required', 'date_greater_or_sameass:feedback_end_date'],
                'penyelarasan_end_date' => ['required', 'date_greater_or_sameass:penyelarasan_start_date'],
                'pengukuran_pertama_start_date' => ['required', 'date_greater_or_sameass:penyelarasan_end_date'],
                'pengukuran_pertama_end_date' => ['required', 'date_greater_or_sameass:pengukuran_pertama_start_date'],
                'pengukuran_kedua_start_date' => ['required', 'date_greater_or_sameass:pengukuran_pertama_end_date'],
                'pengukuran_kedua_end_date' => ['required', 'date_greater_or_sameass:pengukuran_kedua_start_date'],
            ]);
        }

        return $rules;
    }

    protected function getValidatorInstance()
    {
        $this->merge([
            'kelebihan_kekurangan_id' => KelebihanKekurangan::getActiveId(),
        ]);

        return parent::getValidatorInstance();
    }

    public function attributes()
    {
        return [
            'feedback_start_date' => 'Tanggal Mulai Feedback',
            'feedback_end_date' => 'Tanggal Berakhir Feedback',
            'penyelarasan_start_date' => 'Tanggal Mulai Penyelarasan',
            'penyelarasan_end_date' => 'Tanggal Berakhir Penyelarasan',
            'pengukuran_pertama_start_date' => 'Tanggal Mulai Pengukuran Pertama',
            'pengukuran_pertama_end_date' => 'Tanggal Berakhir Pengukuran Pertama',
            'pengukuran_kedua_start_date' => 'Tanggal Mulai Pengukuran Kedua',
            'pengukuran_kedua_end_date' => 'Tanggal Berakhir Pengukuran Kedua',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute harus di isi',
            'feedback_start_date.after' => ':attribute harus setelah tanggal hari ini',
            'feedback_end_date.date_greater_or_sameass' => ':attribute harus setelah atau sama dengan tanggal Mulai Feedback',
            'penyelarasan_start_date.date_greater_or_sameass' => ':attribute harus setelah atau sama dengan tanggal Berakhir Feedback',
            'penyelarasan_end_date.date_greater_or_sameass' => ':attribute harus setelah atau sama dengan tanggal Mulai Penyelarasan',
            'pengukuran_pertama_start_date.date_greater_or_sameass' => ':attribute harus setelah atau sama dengan tanggal Berakhir Penyelarasan',
            'pengukuran_pertama_end_date.date_greater_or_sameass' => ':attribute harus setelah atau sama dengan tanggal Mulai Pengukuran Pertama',
            'pengukuran_kedua_start_date.date_greater_or_sameass' => ':attribute harus setelah atau sama dengan tanggal Berakhir Pengukuran Pertama',
            'pengukuran_kedua_end_date.date_greater_or_sameass' => ':attribute harus setelah atau sama dengan tanggal Berakhir Pengukuran Kedua',
        ];
    }
}
