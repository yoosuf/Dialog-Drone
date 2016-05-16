<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Input;
use ArimacDrone\Stadium\Entity\Score;
use ArimacDrone\Stadium\Entity\Match;

class MatchController extends ApiController
{
    public function submitScores($match_id)
    {
        if (Input::get('score') > 0) {
            Score::create([
                'stand_id' => Input::get('stand_id'),
                'match_id' => $match_id,
                'user_id'  => $this->user->id,
                'score'    => (int) Input::get('score')
            ]);

        }

        return ['success' => true];
    }
}