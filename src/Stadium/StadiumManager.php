<?php

namespace Drone\Stadium;

use ValidationException;
use ArimacDrone\Stadium\Entity\Stadium;
use ArimacDrone\Stadium\Entity\Stand;
use ArimacDrone\Stadium\Entity\Score;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StadiumManager
{
    protected $stadium;

    protected $stand_rules = [
        'name' => 'required|max:32|min:1',
        'lat'  => 'numeric',
        'lng'  => 'numeric',
        'altitude'  => 'numeric',
        'gimbal_pitch'  => 'numeric',
        'yaw_default'  => 'numeric',
        'yaw_start'  => 'numeric',
        'yaw_end'  => 'numeric',
        'type' => 'required|in:standard,center'
    ];

    public function __construct(Stadium $stadium)
    {
        $this->stadium = $stadium;
    }

    public function getStands()
    {
        return $this->stadium->stands;
    }

    public function createStand($data)
    {
        ValidationException::validate($data->getData(), $this->stand_rules);

        $stand             = new Stand($data->getData());
        $stand->stadium_id = $this->stadium->id;
        $stand->save();

        return $stand;
    }

    public function getStand($id)
    {
        return $this->stadium->stands()->findOrFail($id);
    }

    public function updateStand($id, $data)
    {
        $stand = $this->stadium->stands()->findOrFail($id);

        $data  = array_merge($stand->toArray(), $data->getData());
        ValidationException::validate($data, $this->stand_rules);

        $stand->fill($data);
        $stand->save();

        return $stand;
    }

    public function deleteStand($id)
    {
        $this->stadium->stands()->findOrFail($id)->delete();
        return true;
    }

    public function getCenterStand()
    {
        try {
            
            return Stand::where('type', 'center')
                            ->where('stadium_id', $this->stadium->id)
                            ->firstOrFail();

        } catch (ModelNotFoundException $e) {
            throw new ValidationException(['No center stand found for given stadium.']);
        }
    }

    public function getBestStand($match_id)
    {
        try {
            
            return Score::groupBy('stand_id')
                           ->leftJoin('stands', 'scores.stand_id', '=', 'stands.id')
                           ->where('stands.stadium_id', $this->stadium->id)
                           ->where('scores.match_id', $match_id)
                           ->selectRaw('sum(score) as score, stand_id')
                           ->orderBy('score', 'desc')
                           ->firstOrFail()->stand;

        } catch (ModelNotFoundException $e) {
            throw new ValidationException(['No scores exist.']);
        }
    }

    public function getWaypointToStand($stand_id)
    {
        $center = $this->getCenterStand();
        $stand  = $this->getStand($stand_id);

        $way = [
            'mission' => [
                'type' => 'waypoint_nav',
                'description' => "Go To Stand {$stand->name}",
                'start' => $center->getLocation(),
                'end'   => $stand->getLocation()
            ]
        ];

        return $way;
    }

    public function getWayPointToBestStand($match_id)
    {
        return $this->getWaypointToStand($this->getBestStand($match_id)->id);
    }

    public function startSession($match_id)
    {
        //make sure match is live
        

        try {
            
            $center = $this->getCenterStand();

            $way = [
                'mission' => [
                    'type' => 'ground_center_nav',
                    'description' => 'Go to ground center',
                    'end' => $center->getLocation()
                ]
            ];

            return $way;

        } catch (ModelNotFoundException $e) {
            throw new ValidationException(['No center stand found for given stadium.']);
        }
    }
}