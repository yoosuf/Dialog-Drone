<?php

namespace App\Http\Controllers\Admin;

use App\Drone;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;


class PushNotificationController extends AdminController
{




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.push.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\Admin\CreateMatchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Admin\SendPushRequest $request)
    {
        $input = $request->all();

        $title=$input['title'] ;
        $message=$input['message'];
        sendPush($title,$message);
        return redirect()->route('admin.push-notify.create')->with('message', 'Push Notification Successfully Sent.');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}