<?php

namespace App\Http\Controllers\Admin;

use App\Drone;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CreateMatchRequest;

use App\Http\Requests\Admin\UpdateMatchRequest;
use App\Match;
use App\Team;
use App\Stadium;
use App\Utils\ImageUpload;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class MatchesController extends AdminController
{


    protected $match;
    protected $team;
    protected $stadium;
    protected $upload;
    protected $droneCntrl;


    public function __construct(Match $match, Stadium $stadium, Team $team, Drone $droneCntrl, ImageUpload $upload)
    {
        $this->match = $match;
        $this->team = $team;
        $this->stadium = $stadium;
        $this->upload = $upload;
        $this->droneCntrl = $droneCntrl;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->match;
        $today = date('Y-m-d');
        $tomorrow =  date('Y-m-d', time()+86400);

        if(Input::get('filter')== 'live'){

            $data = $data->where('scheduled', 'LIKE', '%'.$today.'%');

        } else if(Input::get('filter')== 'upcoming') {

            $data = $data->where('scheduled', '>', $tomorrow );

        }  else if(Input::get('filter')== 'past') {

            $data = $data->where('scheduled', '<=', $today);
        }

        $data = $data->get();

        return view('admin.matches.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stadiums = $this->stadium->lists('name', 'id');
        return view('admin.matches.create', compact('stadiums'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\Admin\CreateMatchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMatchRequest $request)
    {

        $matchImage=null;
        $teamOneImage=null;
        $teamTwoImage=null;
        $shoutoutImage=null;

        if ($request->hasFile('banner_image')):
            $matchImage = $this->upload->process($request->file('banner_image'), 'matches');
        endif;

        if ($request->hasFile('shoutout_image')):
            $shoutoutImage = $this->upload->process($request->file('shoutout_image'), 'matches');
        endif;

        if ($request->hasFile('team_one_image')):
            $teamOneImage = $this->upload->process($request->file('team_one_image'), 'teams');
        endif;
        if ($request->hasFile('team_two_image')):
            $teamTwoImage = $this->upload->process($request->file('team_two_image'), 'teams');
        endif;

        $matchInputs=[
                      'stadium_id'      =>Input::get('stadium_id'),
                      'name'            =>Input::get('name'),
                      'venue'           =>Input::get('venue'),
                      'scheduled'       =>Input::get('scheduled'),
                      'is_active'       =>Input::has('is_active'),
                      'status'          =>0,
                      'description'     =>Input::get('description'),
                      'live_url'        =>Input::get('live_url'),
                      'interview_url'   =>Input::get('interview_url'),
                      'banner_image'   =>$matchImage,
                      'shoutout_image'   =>$shoutoutImage,
        ];
        $matchObj=$this->match->create($matchInputs);

        $teamInputs=[
            [
            'match_id'        =>$matchObj['id'],
            'name'            =>Input::get('team_one_name'),
            'image'           =>$teamOneImage,
            'score'           =>0
             ],
            [
                'match_id'       =>$matchObj['id'],
                'name'            =>Input::get('team_two_name'),
                'image'           =>$teamTwoImage,
                'score'           =>0
            ]
        ];

        $droneCntrlInit=[
            ['match_id'=>$matchObj['id'],'type'=>'drone'],
            ['match_id'=>$matchObj['id'],'type'=>'selfie'],
            ['match_id'=>$matchObj['id'],'type'=>'quiz'],
            ['match_id'=>$matchObj['id'],'type'=>'vote'],
            ['match_id'=>$matchObj['id'],'type'=>'shoutout'],
                        ];
        $this->team->insert($teamInputs);
        $this->droneCntrl->insert($droneCntrlInit);

        return redirect()->route('admin.matches.index')->with('message', 'Successfully created');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataMatch = $this->match->find($id);
        $dataTeam = $this->team->where('match_id','=',$id)->get();
        $stadiums = $this->stadium->lists('name', 'id');

        if (count($dataMatch) == 0)
            return "No Data Found!";

        return view('admin.matches.edit', compact('dataMatch','dataTeam', 'stadiums'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Requests\UpdateMatchRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMatchRequest $request, $id)
    {

        $matchData = $this->match->find($id);
        $teamData = $this->team->where('match_id','=',$id)->get();
        $matchImage=null;
        $teamOneImage=null;
        $teamTwoImage=null;
        $shoutoutImage=null;

        if ($request->hasFile('banner_image'))
            $matchImage = $this->upload->process($request->file('banner_image'), 'matches');
        else
            $matchImage=$matchData->banner_image;

        if ($request->hasFile('shoutout_image')):
            $shoutoutImage = $this->upload->process($request->file('shoutout_image'), 'matches');
        endif;


        if ($request->hasFile('team_one_image'))
            $teamOneImage = $this->upload->process($request->file('team_one_image'), 'teams');
        else
            $teamOneImage=$teamData[0]->image;

        if ($request->hasFile('team_two_image'))
            $teamTwoImage = $this->upload->process($request->file('team_two_image'), 'teams');
        else
            $teamTwoImage=$teamData[1]->image;


        $matchInputs=[
            'stadium_id'      =>Input::get('stadium_id'),
            'name'            =>Input::get('name'),
            'venue'           =>Input::get('venue'),
            'scheduled'       =>Input::get('scheduled'),
            'is_active'       =>Input::has('is_active'),
            'description'     =>Input::get('description'),
            'live_url'        =>Input::get('live_url'),
            'interview_url'   =>Input::get('interview_url'),
            'banner_image'    =>$matchImage,
            'shoutout_image'   =>$shoutoutImage
        ];


        $teamInputs=[[
                        'name'            =>Input::get('team_one_name'),
                        'image'           =>$teamOneImage
                    ],
                    [
                        'name'            =>Input::get('team_two_name'),
                        'image'           =>$teamTwoImage
                    ]];
        $i=0;
        foreach($teamData as $team){
            DB::table('match_teams')
                ->where('id', $team->id)
                ->update($teamInputs[$i]);
            $i++;
        }

       $matchData->update($matchInputs);
        return redirect()->route('admin.matches.index')->with('message', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->match->find($id);
        if (count($data) == 0)
            return "No Data Found!";

        $data->delete();
        return redirect()->route('admin.matches.index')->with('message', 'Successfully deleted');
    }
}
