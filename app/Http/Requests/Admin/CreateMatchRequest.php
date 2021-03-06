<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CreateMatchRequest extends Request
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
            'venue' => 'required',
            'scheduled' => 'required',
            'banner_image' => 'required|image',
            'shoutout_image' => 'required|image',
            'interview_url' => 'url',
            'live_url' => 'url',
            'team_one_name' => 'required',
            'team_one_image' => 'required|image',
            'team_two_name' => 'required',
            'team_two_image' => 'required|image',
        ];
    }
}
