<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class KelebihanKekuranganRequest extends Request
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
		$rules = [
			'judul_kk'		=> 'required',
			'deskripsi_kk'	=> 'required',
			'status'		=> 'required'
		];
		for ($i=1; $i <= (int)request()->index_kelebihan; $i++) {
			$rules["deskripsi_kelebihan_$i"] = 'required';
			$rules["deskripsi_kekurangan_$i"] = 'required';
			$rules["category_$i"] = 'required';
		}

		if (request()->isMethod('post')) {
			$rules['status'] = 'activate_masdat_kelebihan_kekurangan:'
				.request()->status;
		} else {
			$rules['status'] = 'activate_masdat_kelebihan_kekurangan:'
				.request()->status.','.request()->data_id;
		}

		return $rules;
	}

	public function messages()
	{
		return [
			'status.activate_masdat_kelebihan_kekurangan'
				=> 'Sudah terdapat status aktif pada master data kelebihan kekurangan'
		];
	}
}
