<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\AdminPasswordUpdate;
use App\Http\Requests\Admin\CreateAdminUser;
use App\Http\Requests\Admin\UpdateAdminUser;
use App\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends AdminController
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $data = $this->user->where('is_admin', 1)->where('role_id', '!=' , 1)->with('role')->get();
        return view('admin.users.index', compact('data'));
    }


    public function create()
    {
        return view('admin.users.create');

    }


    public function store(CreateAdminUser $request)
    {

        $input = $request->all();
        $input['is_admin'] = 1;
        $input['login_type'] = 'admin';
        $this->user->create($input);
        return redirect()->route('admin.users.index')->with('message', 'Successfully created');
    }

    public function edit($id)
    {

        $item = $this->user->where('is_admin', 1)->with('role')->find($id);

        return view('admin.users.edit', compact('item'));

    }

    public function update(UpdateAdminUser $request, $id)
    {

        $input = array_except($request->all(), '_method');
        $data = $this->user->where('is_admin', 1)->with('role')->find($id);
        $data->update($input);

        return redirect()->route('admin.users.index')->with('message', 'Successfully updated');
    }

    public function destroy($id)
    {
        $data = $this->user->where('is_admin', 1)->find($id);
        if (count($data) == 0)
            return "No Data Found!";

        $data->delete();
        return redirect()->route('admin.users.index')->with('message', 'Successfully deleted');
    }


    public function getChangePassword() {

        return view('auth.password');

    }

    public function postUpdatePassword(AdminPasswordUpdate $request) {

        //
        $user =  $this->user->where('id', '=' , Auth::user()->id)->first();



        $user->password = $request->get('password');
        $user->update();
        Auth::logout();
        return redirect()->route('admin.dashboard')->with('message', 'Please login with the new password');

    }
}