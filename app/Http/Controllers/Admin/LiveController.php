<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\UpdateLiveRequest;
use App\Match;
use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
class LiveController extends AdminController
{


    protected $live;
    protected $team;
    protected $match;

    public function __construct(Match $live, Team $team, Match $match)
    {
        $this->live = $live;
        $this->team = $team;
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



        $match = $this->match->find($matchId);

        $data = $this->live->find($matchId);
        $data->team;

        $scheduled_date = Carbon::createFromFormat('Y-m-d H:i:s',  $data->scheduled)->format('Y-m-d');
        $today = date('Y-m-d');


        $isLive = false;

        if($scheduled_date == $today)
            $isLive = true;




        if(count($data) == 0)
            return "No Data Found!";

        return view('admin.live.edit', compact('data', 'match', 'isLive'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLiveRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLiveRequest $request, $id)
    {

        $input = array_except($request->all(), '_method');
        $data = $this->live->find($id);

        $matchInputs=[
            'status'        =>isset($input['status']),
            'sub_status'    =>$input['sub_status'],
            'remarks'       =>$input['remarks']
        ];

        $data->update($matchInputs);
        $i=0;
        foreach($data->team as $team){
            $teamObj=$this->team->find($team['id']);
                $teamObj->score=$input['score'][$i];
                $teamObj->save();
            $i++;
        }

        return redirect()->route('admin.matches.index')->with('message', 'Successfully updated');;
    }

}
