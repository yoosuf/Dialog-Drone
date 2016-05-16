<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserReward extends Model
{

    use RecordsActivity, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'user_rewards';

    /*
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function matchReward()
    {
        return $this->belongsTo('App\MatchReward');
    }
    */

}
