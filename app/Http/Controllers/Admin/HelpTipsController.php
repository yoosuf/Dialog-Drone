<?php

namespace App\Http\Controllers\Admin;

use App\Help;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CreateHelpRequest;
use App\Http\Requests\Admin\UpdateHelpRequest;

use App\Http\Requests;
use App\Utils\ImageUpload;

class HelpTipsController extends AdminController
{
    protected $help;
    protected $upload;
    public function __construct(Help $help, ImageUpload $upload)
    {
        $this->help = $help;
        $this->upload = $upload;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->help->all();
        return view('admin.help.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.help.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateHelpRequest|\Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateHelpRequest $request)
    {
        $input = $request->all();
        $input['image'] = $this->upload->process($request->file('image'), 'help');
        $this->help->create($input);

        return redirect()->route('admin.help.index')->with('message', 'Successfully created');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->help->find($id);
        if(count($item) == 0)
            return "No Data Found!";

        return view('admin.help.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHelpRequest $request, $id)
    {
        $input = array_except($request->all(), '_method');
        $data = $this->help->find($id);

        if ($request->hasFile('image'))
            $input['image'] = $this->upload->process($request->file('image'), 'help');
        else
            $input['image'] =  $data->image;

        $data->update($input);

        return redirect()->route('admin.help.index')->with('message', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->help->find($id);
        if(count($data) == 0 )
            return "No Data Found!";

        $data->delete();
        return redirect()->route('admin.help.index')->with('message', 'Successfully deleted');
    }
}
