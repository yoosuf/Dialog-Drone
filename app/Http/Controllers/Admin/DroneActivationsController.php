<?php

namespace App\Http\Controllers\Admin;
use App\Drone;
use App\Http\Controllers\AdminController;
use App\Match;
use Illuminate\Http\Request;
use Input;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class DroneActivationsController extends AdminController
{
    protected $drone;
    protected $match;

    public function __construct(Drone $drone, Match $match)
    {
        $this->drone = $drone;
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
        $data = $this->drone->where('match_id', $matchId)->get();
        $match = $this->match->find($matchId);

        return view('admin.dactivations.activate', compact('data', 'matchId', 'match'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $matchId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function store(Request $request, $matchId)
    {
        $inputs=$request->all();
        $i=0;

        foreach($inputs['id'] as $id) {
            $active=$inputs['is_active'.$id];
            $forceStart=$inputs['force_start'.$id];
            DB::table('drone_controls')
                ->where('id',$id)
                ->update(array(
                    'is_active' =>$active,
                    'start_at' => $inputs['start_at'][$i],
                    'end_at' => $inputs['end_at'][$i],
                    'force_start' =>$forceStart,
                ));

            $i++;
            }
        return redirect()->route('admin.matches.activations.index', $matchId)->with('message', 'Successfully updated');
    }


}
