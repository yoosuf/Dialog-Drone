<?php
namespace Drone\Stadium\Entity;

use Drone\Stadium\Entity\Match\Team;
use App\Model;

class Match extends Model
{
    protected $fillable = [
        'stadium_id', 'name', 'status'
    ];

    protected $strings = ['sub_status', 'description', 'live_url', 'interview_url'];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function activation()
    {
        return $this->hasMany(Activation::class);
    }

    public function getBannerImageAttribute()
    {
        return $this->formatImageUrlAttribute('banner_image');
    }

    public function getShoutoutImageAttribute()
    {
        return $this->formatImageUrlAttribute('shoutout_image');
    }
}