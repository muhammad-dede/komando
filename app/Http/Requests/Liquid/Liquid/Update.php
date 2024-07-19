<?php

namespace App\Http\Requests\Liquid\Liquid;

use App\Http\Requests\Request;
use Carbon\Carbon;

class Update extends Request
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
        ];

        if (!config('liquid.disable_date_validation')) {
            $rules = array_merge($rules, [
                'feedback_start_date' => ['required'],
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

    public function sanitize()
    {
        return [
            'feedback_start_date' => Carbon::parse($this->input('feedback_start_date')),
            'feedback_end_date' => Carbon::parse($this->input('feedback_end_date')),
            'penyelarasan_start_date' => Carbon::parse($this->input('penyelarasan_start_date')),
            'penyelarasan_end_date' => Carbon::parse($this->input('penyelarasan_end_date')),
            'pengukuran_pertama_start_date' => Carbon::parse($this->input('pengukuran_pertama_start_date')),
            'pengukuran_pertama_end_date' => Carbon::parse($this->input('pengukuran_pertama_end_date')),
            'pengukuran_kedua_start_date' => Carbon::parse($this->input('pengukuran_kedua_start_date')),
            'pengukuran_kedua_end_date' => Carbon::parse($this->input('pengukuran_kedua_end_date')),
            'reminder_aksi_resolusi' => $this->input('reminder_aksi_resolusi'),
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
