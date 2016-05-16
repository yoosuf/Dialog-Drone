<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuesAnswered extends Model
{
    use RecordsActivity, SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'user_answers';

    public function ques()
    {
        return $this->belongsTo('App\Question');
    }
}
