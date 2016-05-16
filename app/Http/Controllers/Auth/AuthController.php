<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Cookie\CookieJar;


class AuthController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getDestroy']);
    }

    public function getLogin()
    {
        return view('auth.login');
    }

    public function postIndex(Request $request)
    {
        if (Auth::attempt(['login' => $request->get('login'), 'password' => $request->get('password')])) {
            // Authentication passed...
            if(Auth::check() && !Auth::user()->is_admin)
            {
                Auth::logout();

                return Redirect::to('secured/login');
            } else {

                return redirect()->intended('admin');
            }

        }

        return "There are Validation Errors";

    }


    public function getDestroy() {
        Auth::logout();
        return redirect()->intended('secured/login');
    }
}
