<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{

    use RecordsActivity;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $table = "scores";

    protected $fillable = [
        'stand_id', 'score'
    ];

    public function stands()
    {
        return $this->belongsTo(Stand::class);
    }
}
