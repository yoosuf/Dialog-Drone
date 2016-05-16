<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use ValidationException;
use DB;
use JWTAuth;
use Exception;

class UserMediaController extends Controller
{
    public function __construct()
    {
        try {
            $this->user_id = JWTAuth::parseToken()->authenticate()->id;
        } catch (Exception $e) {
            $this->user_id = 0;
        }

    }

    public function submitShoutout($match_id)
    {
        $rules = [
            'message' => 'required|min:2|max:600',
            'device_id' => 'required'
        ];

        ValidationException::validate(Input::get(), $rules);

        DB::table('user_shoutouts')->insert([
            'match_id' => $match_id,
            'user_id'  => $this->user_id,
            'message'  => Input::get('message'),
            'device_id'=> Input::get('device_id'),
            'status'   => 0
        ]);

        return ['success' => true];
    }

    public function submitSelfie($match_id)
    {
        $rules = [
            'image' => 'required|url|max:255',
            'device_id' => 'required'
        ];

        ValidationException::validate(Input::get(), $rules);

        DB::table('user_selfies')->insert([
            'match_id' => $match_id,
            'user_id'  => $this->user_id,
            'image'    => Input::get('image'),
            'device_id'    => Input::get('device_id'),
            'status'   => 0
        ]);

        return ['success' => true];
    }
}
