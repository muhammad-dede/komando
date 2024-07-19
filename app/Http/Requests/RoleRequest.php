<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RoleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'display_name'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'System name wajib dipilih',
            'display_name.required'=>'Display name wajib diisi'
        ];
    }
}
