<?php
/**
 * Created by PhpStorm.
 * User: yoosuf
 * Date: 27/10/2015
 * Time: 20:02
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stadium extends  Model
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
    protected $table = 'stadiums';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function stand()
    {
        return $this->hasMany('App\Stand');
    }
}