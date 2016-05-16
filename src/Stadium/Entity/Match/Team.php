<?php
namespace Drone\Stadium\Entity\Match;

use App\Model;
use Drone\Stadium\Entity\Match;

class Team extends Model
{
    protected $table = 'match_teams';

    protected $fillable = ['match_id', 'name', 'image', 'score'];

    public function match()
    {
        return $this->belongsTo(Match::class);
    }

    public function getImageAttribute()
    {
        return $this->formatImageUrlAttribute('image');
    }
}