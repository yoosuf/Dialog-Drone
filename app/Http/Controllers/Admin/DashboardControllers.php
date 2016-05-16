<?php

namespace App\Http\Controllers\Admin;

use App\Activity;
use App\Http\Controllers\AdminController;
use App\Http\Requests;
use App\User;

class DashboardControllers extends AdminController
{
    protected $activity;
    protected $user;

    public function __construct(Activity $activity, User $user)
    {
        $this->activity = $activity;
        $this->user = $user;
    }

    public function index() {

//        $user = $this->user->where('is_first_attempt', 1)->first();

//        return $user;
        // $data = $this->activity->all();
        return view('admin.dashboard.index');
    }

}