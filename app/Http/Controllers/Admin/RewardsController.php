<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CreateRewardRequest;
use App\Http\Requests\Admin\UpdateRewardRequest;
use App\Reward;
use App\Utils\ImageUpload;

use App\Http\Requests;

class RewardsController extends AdminController
{

    protected $reward;

    protected $upload;

    public function __construct(Reward $reward, ImageUpload $upload)
    {
        $this->reward = $reward;

        $this->upload = $upload;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->reward->all();
        return view('admin.rewards.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.rewards.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRewardRequest|\Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRewardRequest $request)
    {

        $input = $request->all();

        if ($request->hasFile('image'))
            $input['image'] = $this->upload->process($request->file('image'), 'rewards');

        $this->reward->create($input);
        return redirect()->route('admin.rewards.index')->with('message', 'Successfully created');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->reward->find($id);

        return view('admin.rewards.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRewardRequest $request, $id)
    {
        $input = array_except($request->all(), '_method');
        $data = $this->reward->find($id);
        $data->update($input);
        return redirect()->route('admin.rewards.index')->with('message', 'Successfully updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->reward->find($id);
        $data->delete();
        return redirect()->route('admin.rewards.index')->with('message', 'Successfully deleted');
    }
}
