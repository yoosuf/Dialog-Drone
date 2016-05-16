<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CreateStadiumRequest extends Request
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
            'name' => 'required',
            'lat' =>  'required',
            'lng' =>  'required',
            'map_image' => 'image',
            'icon_image' => 'image',
            'image_width' => 'required',
            'image_height' => 'required',
        ];
    }
}
