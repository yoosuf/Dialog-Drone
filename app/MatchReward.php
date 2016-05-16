<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchReward extends Model
{
    use RecordsActivity, SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    protected $table = 'match_rewards';

    protected $guarded = [];


    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

}