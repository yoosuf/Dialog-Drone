<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reward extends Model
{

    use RecordsActivity, SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    protected $table = 'rewards';


    protected $guarded = [];

    public function match_reward() {

        return $this->hasMany('MatchReward');

    }
}
