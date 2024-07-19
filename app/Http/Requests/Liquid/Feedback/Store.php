<?php

namespace App\Http\Requests\Liquid\Feedback;

use App\Http\Requests\Request;

class Store extends Request
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
        $kelebihanItem = 3;
        $kekuranganItem = 3;

        $rules = [
            'harapan' => ['required'],
            'saran' => ['required'],
            'boxes_kelebihan' => ['required', 'array', 'max:'.$kelebihanItem, 'min:'.$kelebihanItem,],
            'boxes_kekurangan' => ['required', 'array', 'max:'.$kekuranganItem, 'min:'.$kekuranganItem,],
            'boxes_alasan_kelebihan' => ['array'],
            'boxes_alasan_kelebihan' => ['array'],
        ];

        $kelebihan = $this->input('boxes_kelebihan');
        if (array_has($kelebihan, '__OTHER__')) {
            unset($kelebihan['__OTHER__']);
            $this->merge(['boxes_kelebihan' => $kelebihan]);
            $this->merge(['new_kelebihan' => [$this->new_kelebihan]]);
            $rules['new_kelebihan.*'] = ['required', 'string', 'min:50'];
        } else {
            $this->merge(['new_kelebihan' => null]);
        }

        $kekurangan = $this->input('boxes_kekurangan');
        if (array_has($kekurangan, '__OTHER__')) {
            unset($kekurangan['__OTHER__']);
            $this->merge(['boxes_kekurangan' => $kekurangan]);
            $this->merge(['new_kekurangan' => [$this->new_kekurangan]]);
            $rules['new_kekurangan.*'] = ['required', 'string', 'min:50'];
        } else {
            $this->merge(['new_kekurangan' => null]);
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'boxes_kelebihan' => 'Kelebihan',
            'boxes_kekurangan' => 'Kekurangan',
            'new_kelebihan' => 'Kelebihan Lainnya',
            'new_kekurangan' => 'Kekurangan Lainnya'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute harus di isi',
            'boxes_kelebihan.min' => ':attribute yang dipilih setidaknya harus tiga',
            'boxes_kekurangan.min' => ':attribute yang dipilih setidaknya harus tiga',
            'boxes_kelebihan.max' => ':attribute tidak boleh lebih dari tiga',
            'boxes_kekurangan.max' => ':attribute tidak boleh lebih dari tiga',
        ];
    }
}
