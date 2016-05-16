<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CreateMatchRewardRequest;
use App\Http\Requests\Admin\UpdateMatchRewardRequest;
use App\Match;
use App\MatchReward;
use App\Reward;

use App\Http\Requests;
use Illuminate\Support\Facades\Request;

class MatchRewardsController extends AdminController
{

    protected $rewards;
    protected $match;
    protected $allReward;

    public function __construct(MatchReward $reward, Match $match, Reward $allReward)
    {
        $this->rewards = $reward;
        $this->match = $match;
        $this->allReward = $allReward;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $matchId
     * @return \Illuminate\Http\Response
     */
    public function index($matchId)
    {
        $data = $this->rewards->where('match_id', '=', $matchId)->get();
        $match = $this->match->find($matchId);


        return view('admin.match_rewards.index', compact('data', 'matchId', 'match'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($matchId)
    {

        $matches = $this->match->lists('name', 'id');

        $rewards =  $this->allReward->lists('title', 'id');

        $match = $this->match->find($matchId);


        return view('admin.match_rewards.create', compact('matches', 'rewards', 'matchId', 'match'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateMatchRewardRequest|\Illuminate\Http\Request $request
     * @param $matchId
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMatchRewardRequest $request, $matchId)
    {
        $input = $request->all();
        $input['match_id'] = $matchId;
        $this->rewards->create($input);
        return redirect()->route('admin.matches.rewards.index', $matchId)->with('message', 'Successfully created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $matchId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($matchId, $id)
    {
        $rewards =  $this->allReward->lists('title', 'id');

        $item = $this->rewards->find($id);
        if(count($item) == 0)
            return "No Data Found!";

        $match = $this->match->find($matchId);

        return view('admin.match_rewards.edit', compact('item', 'rewards', 'matchId', 'match'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMatchRewardRequest|\Illuminate\Http\Request $request
     * @param $matchId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMatchRewardRequest $request, $matchId, $id)
    {
        $input = array_except($request->all(), '_method');
        $data = $this->rewards->find($id);

        $data->update($input);

        return redirect()->route('admin.matches.rewards.index', $matchId)->with('message', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $matchId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($matchId, $id)
    {
        $data = $this->rewards->find($id);
        $data->delete();
        return redirect()->route('admin.matches.rewards.index', $matchId)->with('message', 'Successfully deleted');
    }
}
