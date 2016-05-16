<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;

use App\Http\Requests;
use App\Match;
use App\Score;
use Illuminate\Support\Facades\DB;


class ScoreController extends AdminController
{

    protected $score;
    protected $match;


    public function __construct(Score $score,  Match $match)
    {
        $this->score = $score;
        $this->match = $match;

    }

    public function index($matchId) {
        $match = $this->match->find($matchId);

//        $data = DB::select('SELECT id, match_id, stand_id, SUM(score) as score FROM scores WHERE match_id = ? LEFT JOIN stands ON stands.id = scores.stand_id GROUP BY stand_id', [$matchId]);

        /*
         * 1 get stadium form match table
         * 3. get stands
         * 4. get scores
         * 4
         */



//        SELECT id, match_id, stand_id, SUM(score) as score FROM scores
//        WHERE match_id = ?
//        LEFT JOIN matches ON stands.match_id = matches.id
//        LEFT JOIN stadiums ON matchs.stadium_id = stadiums.id
//        LEFT JOIN stands ON scores.stand_id = stands.id
//        GROUP BY scores.stand_id

//        $data = DB::select('SELECT id, match_id, stand_id, SUM(score) as score
//FROM scores
//WHERE scores.match_id = ?
////LEFT JOIN matches ON scores.match_id = matches.id
////LEFT JOIN stadiums ON matchs.stadium_id = stadiums.id
//
//LEFT JOIN stands ON scores.stand_id = stands.id
//GROUP BY scores.stand_id', [$matchId]);

//        $data = $this->score
//            ->groupBy('stand_id')->where('match_id', $matchId)
//            ->select([DB::raw('scores.id, match_id, stand_id, stands.name as stand_name, stands.type as stand_type, SUM(score) as score')])
//            ->leftJoin('stands', 'scores.stand_id', '=', 'stands.id')
//            ->groupBy('stand_id')
//            ->get();

        // SELECT s.id as 'stand id' , (SELECT SUM(score) FROM `scores` WHERE `stand_id` = s.id and match_id = 1) as 'score' FROM stands s where stadium_id = (SELECT stadium_id from matches where id = 1)

        $data = DB::select( DB::raw("SELECT s.id as 'stand_id' , (SELECT  name FROM stands WHERE id = s.id) as 'stand_name',  (SELECT  type FROM stands WHERE id = s.id) as 'stand_type' , (SELECT SUM(score) FROM `scores` WHERE `stand_id` = s.id and match_id = '$matchId') as 'score' FROM stands s where stadium_id = (SELECT stadium_id from matches where id = '$matchId')") );
//        $data = $this->score
//            ->select([DB::raw('scores.id, match_id, matches.stadium_id, stand_id, stands.name as stand_name, stands.type as stand_type, SUM(score) as score')])
//            ->where('match_id', $matchId)
//            ->leftJoin('matches', 'scores.match_id', '=', 'matches.id')
//            ->leftJoin('stands', 'scores.stand_id', '=', 'stands.id')
//            ->groupBy('stand_id')
//            ->get();

//        return $data;

         return view('admin.score.index', compact('data', 'matchId', 'match'));
    }


    public function clear($matchId, $standId) {

        $this->score->where('match_id', '=', $matchId)->where('stand_id', '=', $standId)->delete();


        return redirect()->route('admin.matches.score.index', $matchId)->with('message', 'Stand score successfully cleared!');
    }



    public function update($matchId) {


       $this->score->where('match_id', '=', $matchId)->delete();

        return redirect()->route('admin.matches.score.index', $matchId)->with('message', 'Total Score successfully cleared!');

    }


}