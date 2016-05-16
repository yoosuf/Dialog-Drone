<?php
namespace Drone\Users\Entity;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Drone\Users\Exception\InvalidMobileVerificationCodeException;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'login', 'mobile', 'profile_image', 'is_activated', 'login_type'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'mobile_verification_code'];

    public function setMobileVerificationCode($code)
    {
        $this->mobile_verification_code = $code;
    }

    public function activateFromMobile($verification_code)
    {
        if (!empty($verification_code) && $this->mobile_verification_code == $verification_code) {
            $this->is_activated = 1;
            $this->save();
            return true;
        } else {
            throw new InvalidMobileVerificationCodeException("The provided verification code is invalid.");
        }
    }

    public function getIsActivatedAttribute()
    {
        return (bool) $this->attributes['is_activated'];
    }
}
