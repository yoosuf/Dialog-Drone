<?php
namespace Drone\Stadium\Entity;

use App\Model;

class Stadium extends Model
{
    protected $table = 'stadiums';
    
    protected $fillable = [
        'name', 'image_width', 'image_height', 'map_image', 'icon_image'
    ];

    public function stands()
    {
        return $this->hasMany('Drone\Stadium\Entity\Stand');
    }

    public function scores()
    {
        return $this->hasMany('Drone\Stadium\Entity\Score');
    }

    public function getImageWidthAttribute()
    {
        return (int) $this->attributes['image_width'];
    }

    public function getImageHeightAttribute()
    {
        return (int) $this->attributes['image_height'];
    }

    public function getMapImageAttribute()
    {
        return $this->formatImageUrlAttribute('map_image');
    }

    public function getIconImageAttribute()
    {
        return $this->formatImageUrlAttribute('icon_image');
    }
}