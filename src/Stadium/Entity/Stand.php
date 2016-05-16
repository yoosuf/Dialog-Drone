<?php
namespace Drone\Stadium\Entity;

use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    protected $fillable = [
        'name', 'lat', 'lng', 'altitude', 'gimbal_pitch', 'type',
        'yaw_default', 'yaw_start', 'yaw_end', 'image_x', 'image_y'
    ];

    public function getLocation()
    {
        return [
            'name'         => $this->name,
            'latitude'     => $this->lat,
            'longitude'    => $this->lng,
            'altitude'     => $this->altitude,
            'type'         => $this->type,
            'gimbal_pitch' => $this->gimbal_pitch,
            'yaw_default'  => $this->yaw_default,
            'yaw_start'    => $this->yaw_start,
            'yaw_end'      => $this->yaw_end
        ];
    }

    public function stadiums()
    {
        return $this->belongsTo('Drone\Stadium\Entity\Stadium');
    }

    public function scores()
    {
        return $this->hasMany('Drone\Stadium\Entity\Score');
    }

    public function getLatAttribute()
    {
        return round($this->attributes['lat'], 7);
    }

    public function getLngAttribute()
    {
        return round($this->attributes['lng'], 7);
    }

    public function getAltitudeAttribute()
    {
        return (float) $this->attributes['altitude'];
    }

    public function getGimbalPitchAttribute()
    {
        return (float) $this->attributes['gimbal_pitch'];
    }

    public function getYawDefaultAttribute()
    {
        return (float) $this->attributes['yaw_default'];
    }

    public function getYawStartAttribute()
    {
        return (float) $this->attributes['yaw_start'];
    }

    public function getYawEndAttribute()
    {
        return (float) $this->attributes['yaw_end'];
    }

    public function getImageXAttribute()
    {
        return (int) $this->attributes['image_x'];
    }

    public function getImageYAttribute()
    {
        return (int) $this->attributes['image_y'];
    }
}