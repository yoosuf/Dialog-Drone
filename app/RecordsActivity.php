<?php
/**
 * Created by PhpStorm.
 * User: yoosuf
 * Date: 11/11/2015
 * Time: 11:25
 */

namespace App;

use Illuminate\Support\Facades\Auth;
use ReflectionClass;

trait RecordsActivity
{

    protected static function bootRecordsActivity() {
        foreach (static::getModelEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }


    protected function recordActivity($event) {
        Activity::create([
            'event_id' => $this->id,
            'model' => get_class($this),
            'action' => $this->getActivityName($this, $event),
            'data' => '',
            'user_id' => $this->user_id ? $this->user_id : Auth::user()->id
        ]);
    }

    protected function  getActivityName($model, $action) {
        $name = strtolower((new ReflectionClass($model))->getShortName());
        return "{$action}_{$name}";
    }

    protected static function getModelEvents() {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }
        return [
            'created', 'deleted', 'updated'
        ];
    }
}