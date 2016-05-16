<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\UpdateSelfieRequest;
use App\Http\Requests\Request;
use App\Match;
use App\Selfie;
use Illuminate\Support\Facades\Input;

class SelfiesController extends AdminController
{
    protected $selfie;

    protected $match;


    public function __construct(Selfie $selfie, Match $match)
    {
        $this->selfie = $selfie;

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


        $data =  $this->selfie->where('match_id',$matchID);

        if(Input::get('filter') == "pending") {

            $data = $data->where('status', '=',0);

        } elseif (Input::get('filter') == "approved") {

            $data = $data->where('status', '=',1);

        } elseif (Input::get('filter') == "rejected") {

            $data = $data->where('status', '=',2);

        }

        $data = $data->orderby('status', 'ASC')->orderby('created_at', 'DESC')->with('user')->get();



//        return $data;


//        @todo
//        Why this foreach loop?
//
//        foreach($data as $d){
//            $d['name']='Anonymous User';
//            if(isset($d->user->name)) {
//                $d['name'] = $d->user->name;
//            }
//
//        }


        return view('admin.selfies.index', compact('data', 'match', 'matchID'));
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

        $data = $this->selfie->where('match_id', '=', $matchId)->whereId($id)->first();
        $data['name']='Anonymous User';
        if(isset($data->user->name)) {
            $data['name'] = $data->user->name;
        }
        $match = $this->match->find($matchId);

        if(count($data) == 0 )
            return "No Data Found!";
        return view('admin.selfies.edit', compact('data', 'matchId', 'match'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSelfieRequest|\Illuminate\Http\Request $request
     *
     * @param $matchId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSelfieRequest $request, $matchId,  $id)
    {
        $input = array_except($request->all(), '_method');
        $data = $this->selfie->find($id);
        $data->update($input);
        return redirect()->route('admin.matches.selfies.index', $matchId)->with('message', 'Successfully updated');
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
        $data = $this->selfie->where('match_id', '=', $matchId)->where('id','=',$id)->first();

        if(count($data) == 0)
            return "No Data Found!";

        $data->delete();
        return redirect()->route('admin.matches.selfies.index', $matchId)->with('message', 'Successfully deleted');
    }

}