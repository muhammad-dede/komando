<?php

namespace App\Http\Requests\MediaKit;

use App\Enum\MediaKitJenis;
use App\Http\Requests\Request;

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
            'judul' => ['required'],
            'jenis' => ['required'],
            'status' => ['required'],
            'media.*' => ['required'],
        ];

        if (request()->get('jenis') === MediaKitJenis::VIDEO) {
            array_push($rules['media.*'], 'max:'.(config('komando.max_upload_video')*1000), 'mimes:mp4,webm');
        } else {
            array_push($rules['media.*'], 'max:'.(config('komando.max_upload_image')*1000), 'mimes:png,jpeg,jpg');
        }

        return $rules;
    }
}
