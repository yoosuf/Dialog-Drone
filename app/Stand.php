<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stand extends Model
{

    use RecordsActivity, SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    protected $table = "stands";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


//    public static $rules = [
//        'name' => 'required|max:32|min:1',
//        'lat'  => 'numeric',
//        'lng'  => 'numeric',
//        'altitude'  => 'numeric',
//        'gimbal_pitch'  => 'numeric',
//        'yaw'  => 'numeric',
//        'type' => 'in:standard,center'
//    ];
//
//    protected $fillable = [
//        'name', 'stadium_id',  'lat', 'lng', 'altitude', 'gimbal_pitch', 'yaw', 'type'
//    ];

    public function getLocation()
    {
        return $this->toArray();
    }

    public function getLatAttribute()
    {
        return (float) $this->attributes['lat'];
    }

    public function getLngAttribute()
    {
        return (float) $this->attributes['lng'];
    }

    public function getAltitudeAttribute()
    {
        return (float) $this->attributes['altitude'];
    }

    public function getGimbalPitchAttribute()
    {
        return (float) $this->attributes['gimbal_pitch'];
    }

    public function getYawAttribute()
    {
        return (float) $this->attributes['yaw'];
    }

    public function stadium()
    {
        return $this->belongsTo('App\Stadium');
    }


    public function score() {
        return $this->hasMany(Score::class);

    }
}
