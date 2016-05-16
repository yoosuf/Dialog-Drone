<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CreateStadiumRequest;
use App\Http\Requests\Admin\UpdateStadiumRequest;
use App\Stadium;
use App\Utils\ImageUpload;

class StadiumsController extends AdminController
{


    protected $stadium;

    protected $upload;

    public function __construct(Stadium $stadium, ImageUpload $upload)
    {
        $this->stadium = $stadium;

        $this->upload = $upload;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->stadium->all();
        return view('admin.stadiums.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.stadiums.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateStadiumRequest|\Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStadiumRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('map_image'))
            $input['map_image'] = $this->upload->process($request->file('map_image'), 'stadiums');

        if ($request->hasFile('icon_image'))
            $input['icon_image'] = $this->upload->process($request->file('icon_image'), 'stadiums');

        $this->stadium->create($input);


        return redirect()->route('admin.stadiums.index')->with('message', 'Successfully created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->stadium->find($id);

        if(count($data) == 0 )
            return "No Data Found!";

        return view('admin.stadiums.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStadiumRequest|\Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStadiumRequest $request, $id)
    {
        $input = array_except($request->all(), '_method');
        $data = $this->stadium->find($id);

        if ($request->hasFile('map_image'))
            $input['map_image'] = $this->upload->process($request->file('map_image'), 'stadiums-map');
        else
            $input['map_image'] =  $data->map_image;

        if ($request->hasFile('icon_image'))
            $input['icon_image'] = $this->upload->process($request->file('icon_image'), 'stadiums-icon');
        else
            $input['icon_image'] = $data->icon_image;

        $data->update($input);
        return redirect()->route('admin.stadiums.index')->with('message', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->stadium->find($id);
        if(count($data) == 0 )
            return "No Data Found!";

        $data->delete();
        return redirect()->route('admin.stadiums.index')->with('message', 'Successfully deleted');
    }

}