<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shoutout extends Model
{
    use RecordsActivity, SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_shoutouts';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function match()
    {
        return $this->belongsTo('App\Match');
    }
}
