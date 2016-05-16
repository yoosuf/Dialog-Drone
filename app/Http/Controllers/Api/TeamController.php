<?php

namespace App\Http\Controllers\API;

use App\Match;
use App\TeamPlayer;
use App\TeamPlayerVote;
use Input;
use ValidationException;
use App\Http\Requests;
use App\Http\Controllers\ApiController;

class TeamController extends ApiController
{
    public function getTeamPlayers($matchID, $teamID)
    {

        $teamPlayers = TeamPlayer::where('match_id', $matchID)
                                 ->where('match_team_id', $teamID);

        if (count($teamPlayers->get()) > 0) {
            foreach ($teamPlayers->get() as $teamPlayer) {

                $response[] = [
                    'id' => $teamPlayer->id,
                    'name' => $teamPlayer->name,
                    'image' => asset('/') . $teamPlayer->image
                ];
            }

            return \Response::json(['data' => $response]);
        }else{
            return \Response::json(['errors' =>['Players not found']],404);
        }
    }

    public  function voteTeamPlayer()
    {
        $input = array_except(Input::all(), '_method');
        $matchID=$input['match_id'];
        $teamPlayerID=$input['player_id'];
        $vote=$input['vote'];
        $ifExist = TeamPlayerVote::where('match_id', $matchID)
                                     ->where('team_player_id', $teamPlayerID)
                                     ->where('user_id', $this->user->id);

        if (count($ifExist->get()) < 1) {
            Match::findOrFail($matchID);
            TeamPlayer::findOrFail($teamPlayerID);

            $doVote=new TeamPlayerVote();
            $doVote->match_id=$matchID;
            $doVote->team_player_id=$teamPlayerID;
            $doVote->user_id=$this->user->id;
            $doVote->vote=$vote;
            $doVote->save();
            return \Response::json(array('success' => true));
        }
        throw new ValidationException(['Already voted to the player.']);
    }
}
