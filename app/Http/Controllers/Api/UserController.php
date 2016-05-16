<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Input;
use ValidationException;
use DB;

class UserController extends ApiController
{
    public function me()
    {
        return $this->user;
    }

    public function update()
    {
        return $this->auth->updateUser($this->user, Input::all());
    }

    public function requestMobileValidation()
    {
        $code = $this->auth->sendMobileVerificationCode($this->user);
        return ['code' => $code];
    }

    public function validateMobile()
    {
        $this->auth->activateUserFromMobileCode($this->user, Input::get('code'));
        return ['success' => true];
    }

}