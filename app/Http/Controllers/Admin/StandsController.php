<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CreateStandRequest;
use App\Http\Requests\Admin\UpdateStandRequest;
use App\Stadium;
use App\Stand;

class StandsController extends AdminController
{


    protected $stadium;

    protected $stand;

    public function __construct(Stadium $stadium, Stand $stand)
    {
        $this->stand = $stand;

        $this->stadium = $stadium;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($stadiumId)
    {
        $data = $this->stand->where('stadium_id', $stadiumId)->orderby('stadium_id', 'asc')->get();

        $stadium = $this->stadium->find($stadiumId);


        foreach($data as $d){
            $d['stadium']=$d->stadium->name;
        }
        return view('admin.stands.index', compact('data', 'stadium', 'stadiumId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $stadiumId
     * @return \Illuminate\Http\Response
     */
    public function create($stadiumId)
    {

        $stadiums = $this->stadium->lists('name', 'id');

        $stadium = $this->stadium->find($stadiumId);

        return view('admin.stands.create', compact('stadiums', 'stadiumId', 'stadium'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateStandRequest|\Illuminate\Http\Request $request
     * @param $stadiumId
     * @return \Illuminate\Http\Response
     */

    public function store(CreateStandRequest $request, $stadiumId)
    {
        $data = $this->stand->where('stadium_id', '=', $request->stadium_id)->where('type', '=', 'center')->first();

        if($request->type == 'center' && count($data) == 1 )
            return "The Center type is already exist";

        $input = $request->all();
        $input['stadium_id'] = $stadiumId;
        $this->stand->create($input);
        return redirect()->route('admin.stadiums.stands.index', $stadiumId)->with('message', 'Successfully created');

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @param $stadiumId
     * @return \Illuminate\Http\Response
     */
    public function edit($stadiumId, $id)
    {

        $stadiums = $this->stadium->lists('name', 'id');

        $data = $this->stand->find($id);

        if(count($data) == 0)
            return "No Data Found!";

        $stadium = $this->stadium->find($stadiumId);

        return view('admin.stands.edit', compact('data', 'stadiums', 'stadiumId', 'stadium'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStandRequest|\Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStandRequest $request, $id, $stadiumId)
    {
        $input = array_except($request->all(), '_method');
        $data = $this->stand->find($stadiumId);
        $data->update($input);
        return redirect()->route('admin.stadiums.stands.index', $id)->with('message', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $stadiumId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($stadiumId, $id)
    {
        $data = $this->stand->find($id);
        if(count($data) == 0)
            return "No Data Found!";

        $data->delete();
        return redirect()->route('admin.stadiums.stands.index', $stadiumId)->with('message', 'Successfully deleted');

    }
}