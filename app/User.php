<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

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
    protected $fillable = ['name', 'email', 'login', 'mobile', 'profile_image', 'is_activated', 'password', 'is_admin', 'login_type', 'role_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'mobile_verification_code', 'is_activated'];

    /**
     * @param $code
     */
    public function setMobileVerificationCode($code)
    {
        
    }

    /**
     * @param $verification_code
     * @return bool
     * @throws InvalidMobileVerificationCodeException
     */
    public function activateFromMobile($verification_code)
    {
        if ($this->mobile_verification_code === $verification_code) {
            $this->is_activated = 1;
            $this->save();
            return true;
        } else {
            throw new InvalidMobileVerificationCodeException("The provided verification code is invalid.");
        }
    }

    /**
     * @return bool
     */
    public function getIsActivatedAttribute()
    {
        return (bool) $this->attributes['is_activated'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function selfie()
    {
        return $this->hasMany(Selfie::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shoutOut()
    {
        return $this->hasMany(Shoutout::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role() {
        return $this->belongsTo(Role::class);
    }



    /**
     * Hash the password attribute
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
