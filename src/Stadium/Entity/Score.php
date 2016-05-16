<?php
namespace Drone\Stadium\Entity;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        'stand_id', 'score', 'match_id', 'user_id'
    ];

    public function stand()
    {
        return $this->belongsTo('Drone\Stadium\Entity\Stand');
    }
}
