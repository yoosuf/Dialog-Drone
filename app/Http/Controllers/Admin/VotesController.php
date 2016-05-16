<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Match;
use App\TeamPlayer;
use Illuminate\Http\Request;

use App\Http\Requests;

class VotesController extends AdminController
{

    protected $vote;
    protected $match;

    public function __construct(TeamPlayer $vote, Match $match)
    {
        $this->vote = $vote;
        $this->match = $match;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $matchId
     * @return \Illuminate\Http\Response
     */
    public function index($matchId)
    {
        $data = $this->vote->where('match_id', '=', $matchId)->get();
        $match = $this->match->find($matchId);

        return view('admin.votes.index', compact('data', 'matchId', 'match'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param null $matchId
     * @return \Illuminate\Http\Response
     */
    public function create($matchId)
    {
        $match = $this->match->find($matchId);

        $matches = $this->match->lists('name', 'id');

        return view('admin.votes.create', compact('matches', 'match'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request->all();
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($matchId, $id)
    {
        $match = $this->match->find($matchId);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
