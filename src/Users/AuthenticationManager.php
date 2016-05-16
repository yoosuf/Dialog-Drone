<?php
namespace Drone\Users;

use Drone\Users\Entity\User;
use App\Exceptions\ValidationException;
use Drone\Shared\Exceptions\DomainException;
use App;
use DB;

class AuthenticationManager
{
    public function updateUser($user, $data)
    {
        $rules = [
            'name' => 'required|max:80',
            'login' => "required|max:255|unique:users,login,{$user->id}",
            'email' => 'email|max:60',
            'password' => 'min:5|max:60',
            'mobile' => 'max:11',
            'profile_image' => 'max:400'
        ];

        $defaults = array_only($user->toArray(), array_keys($rules));

        ValidationException::validate(($data->getData() + $defaults), $rules);

        $user->fill($data->getData());

        if ($data->password)
            $user->password = bcrypt($data->password);

        $user->save();

        return $user;
    }

    public function createUser($data, $activated = 0)
    {
        $rules = [
            'name' => 'required|max:80',
            'login' => 'required|max:255|unique:users',
            'email' => 'email|max:60',
            'password' => 'required|min:5|max:60',
            'mobile' => 'max:11',
            'profile_image' => 'max:400',
            'login_type' => 'required|max:255'
        ];

        ValidationException::validate($data->getData(), $rules);

        //create
        $u = new User;
        $u->fill($data->getData());
        $u->is_activated = $activated;
        $u->password     = bcrypt($data->password);
        $u->save();

        return $u;
    }

    public function sendMobileVerificationCode($user)
    {
        //genarate code
        $code = rand(1234, 9789);

        //save
        $user->setMobileVerificationCode($code);
        $user->save();

        //send sms
        
        if (App::environment() != 'testing') {
            $message = "Your Papare App SMS Verification code is {$code}";
            $this->sendSms($user, $message);
        }

        return $code;
    }

    public function activateUserFromMobileCode($user, $code)
    {
        $user->activateFromMobile($code);        
        return $user;
    }

    public function initiateMobilePasswordReset($mobile)
    {
        $user  = User::where('mobile', $mobile)->firstOrFail(); 

        $code  = rand(1234, 9789);
        $table = DB::table('mobile_password_resets');

        $table
            ->where('user_id', $user->id)
            ->delete();

        $table
            ->insert([
                'user_id' => $user->id,
                'mobile'  => $user->mobile,
                'code'    => $code
            ]); 
        
        if (App::environment() != 'testing') {
            $message = "Your Papare App Password Reset Code is {$code}";
            $this->sendSms($user, $message);
        }
    }

    public function doMobilePasswordReset($mobile, $code, $password)
    {
        $rules = [
            'password' => 'min:5|max:60'
        ];

        ValidationException::validate(compact('password'), $rules);

        $table = DB::table('mobile_password_resets');

        $entry = $table
                    ->where('mobile', $mobile)
                    ->where('code', $code)
                    ->first();
        
        if (empty($entry))
            throw new DomainException("Password reset code is invalid or no password reset requests found.");
        
        $user = User::findOrFail($entry->user_id);
        $user->password = bcrypt($password);
        $user->save();

        return $user;
    }

    private function sendSms($user, $message)
    {
        $mobile  = $user->mobile;
        
        if (empty($mobile))
            throw new DomainException('No valid user mobile found');

        sendSms($mobile, $message);
    }
}