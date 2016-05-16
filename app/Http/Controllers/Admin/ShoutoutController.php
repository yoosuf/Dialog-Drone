<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\UpdateShoutoutRequest;
use App\Match;
use App\Shoutout;
use Illuminate\Support\Facades\Input;

class ShoutoutController extends AdminController
{

    protected $shoutout;
    protected $match;


    public function __construct(Shoutout $shoutout, Match $match)
    {
        $this->shoutout = $shoutout;
        $this->match = $match;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $matchID
     * @return \Illuminate\Http\Response
     */
    public function index($matchID)
    {
        $match = $this->match->find($matchID);

        $data =  $this->shoutout->where('match_id',$matchID);

        if(Input::get('filter') == "pending") {

            $data = $data->where('status', '=',0);

        } elseif (Input::get('filter') == "approved") {

            $data = $data->where('status', '=',1);

        } elseif (Input::get('filter') == "rejected") {

            $data = $data->where('status', '=',2);

        }

        $data = $data->orderby('status', 'ASC')->orderby('created_at', 'DESC')->get();


//       Why is this?
//        if(count($data) == 0 )
//            return "No Data Found!";
//        foreach($data as $d){
//            $d['name']='Anonymous User';
//            if(isset($d->user->name)) {
//                $d['name'] = $d->user->name;
//            }
//
//            $d['match_name']=$d->match->name;
//        }


        return view('admin.shoutouts.index', compact('data', 'match', 'matchID'));
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
        $match = $this->match->find($matchId);


        $data = $this->shoutout->find($id);
        if(count($data) == 0 )
            return "No Data Found!";
        $data['name']='Anonymous User';
        if(isset($data->user->name)) {
            $data['name'] = $data->user->name;
        }

        return view('admin.shoutouts.edit', compact('data', 'matchId', 'match'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateShoutoutRequest $request
     * @param $matchId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShoutoutRequest $request,  $matchId , $id)
    {
        $input = array_except($request->all(), '_method');
        $data = $this->shoutout->find($id);
        $data->update($input);
        return redirect()->route('admin.matches.shoutouts.index', $matchId)->with('message', 'Successfully updated');
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
        $data = $this->shoutout->where('match_id', '=', $matchId)->where('id','=',$id)->first();

        if(count($data) == 0)
            return "No Data Found!";

        $data->delete();
        return redirect()->route('admin.matches.shoutouts.index', $matchId)->with('message', 'Successfully deleted');
    }
}