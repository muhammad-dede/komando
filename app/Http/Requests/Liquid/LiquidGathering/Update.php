<?php

namespace App\Http\Requests\Liquid\LiquidGathering;

use App\Http\Requests\Request;

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
        return [
            'gathering_start_date' => ['date_format:d-m-Y'],
            'gathering_end_date' => ['date_format:d-m-Y', 'date_greater_or_sameass:gathering_start_date'],
        ];
    }

    public function sanitize()
    {
        return [
            'gathering_start_date' => app_parse_date($this->input('gathering_start_date')),
            'gathering_end_date' => app_parse_date($this->input('gathering_end_date')),
            'gathering_location' => $this->input('gathering_location'),
            'link_meeting' => $this->input('link_meeting'),
            'keterangan' => $this->input('keterangan'),
        ];
    }

    public function attributes()
    {
        return [
            'gathering_start_date' => 'Tanggal Mulai',
            'gathering_end_date' => 'Tanggal Selesai',
        ];
    }

    public function messages()
    {
        return [
            'gathering_end_date.date_greater_or_sameass' => ':attribute harus lebih besar atau sama dengan Tanggal Mulai',
        ];
    }
}
