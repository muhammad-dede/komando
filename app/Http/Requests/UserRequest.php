<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
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
            'company'=>'required',
            'username'=>'required',
            'email'=>'required|email',
            'nip'=>'required',
            'posisi'=>'required',
            'orgeh'=>'required',
            'business_area'=>'required|array|min:1',
            'roles'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'company.required' => 'Unit wajib diisi',
            'username.required' => 'Username wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email salah',
            'nip.required' => 'NIP wajib diisi',
            'posisi.required' => 'Posisi wajib diisi',
            'orgeh.required' => 'Organisasi wajib dipilih',
            'business_area.required' => 'Business Area wajib dipilih',
            'business_area.min' => 'Business Area wajib dipilih minimal 1',
            'roles.required' => 'Role wajib dipilih',
        ];
    }
}
