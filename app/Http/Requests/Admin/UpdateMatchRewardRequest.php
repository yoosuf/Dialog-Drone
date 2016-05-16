<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class UpdateMatchRewardRequest extends Request
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
                        'no_of_rewards' => 'required',
            'expire' => 'required',
            'start' => 'required',
            'end' => 'required',
            'counter_pin' => 'required'
        ];
    }
}
