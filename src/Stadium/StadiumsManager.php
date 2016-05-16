<?php
namespace Drone\Stadium;

use Drone\Stadium\Entity\Stadium;
use ValidationException;

class StadiumsManager
{

    public function listStadiums()
    {
        return Stadium::all();
    }

    public function getStadium($id)
    {
        return Stadium::findOrFail($id);
    }

    public function createStadium($name)
    {
        $params = ['name' => $name];

        ValidationException::validate($params, ['name' => 'required|min:3|max:65']);
        return Stadium::create($params);
    }

    public function getStadiumManager($id)
    {
        return new StadiumManager(Stadium::findOrFail($id));
    }

    public function deleteStadium($id)
    {
        $stadium = Stadium::findOrFail($id);

        foreach ($stadium->stands as $stand) {
            $stand->scores()->delete();
            $stand->delete();
        }

        return true;
    }
}