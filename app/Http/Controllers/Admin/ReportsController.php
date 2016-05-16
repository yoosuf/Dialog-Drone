<?php
/**
 * Created by PhpStorm.
 * User: Yoosuf
 * Date: 12/3/2015
 * Time: 7:14 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AdminController;
use App\Match;
use App\Selfie;
use App\Shoutout;
use App\User;
use Illuminate\Support\Facades\Input;

class ReportsController extends AdminController
{

    protected $users;
    protected $selfies;
    protected $shoutouts;
    protected $matchs;



    public function __construct(User $users, Selfie $selfies, Shoutout $shoutouts, Match $matchs)
    {
        $this->users = $users;
        $this->selfies = $selfies;
        $this->shoutouts = $shoutouts;
        $this->matchs = $matchs;
    }

    public function index()
    {
        return view('admin.reports.index');

    }


    public function users()
    {
        $from = Input::get('from');
        $to = Input::get('to');



        $data = $this->users->whereNotIn('role_id', [1,2,3,4]);

        if(Input::get('login-type')== 'facebook'){

            $data = $data->where('login_type', '=', 'facebook');

        } else if(Input::get('login-type')== 'google') {

            $data = $data->where('login_type', '=', 'google' );

        } else if(Input::get('login-type')== 'email') {

            $data = $data->where('login_type', '=', 'google' );
        }

        if($from && $to) {

            $data = $data->whereBetween('created_at', [$from, $to] );
        }

        $data = $data->get();

        return view('admin.reports.users', compact('data'));
    }


    public function shoutouts()
    {

//        $matches = $this->matchs->lists('id', 'name');

        $matches = Match::lists('name', 'id');

//        return $matches;


        $data =  $this->shoutouts;

//        return $data;
//
        if(Input::get('match_id') == Input::has('match_id')) {
            $data= $data->where('match_id', Input::get('match_id'));
        }

        if(Input::get('filter') == "pending") {

            $data = $data->where('status', '=',0);

        } elseif (Input::get('filter') == "approved") {

            $data = $data->where('status', '=',1);

        } elseif (Input::get('filter') == "rejected") {

            $data = $data->where('status', '=',2);
        }

        $data = $data->with('user')->get();

        return view('admin.reports.shoutouts', compact('data', 'matches'));

    }


    public function selfies()
    {
        $matches = Match::lists('name', 'id');

//        return $matches;


        $data =  $this->selfies;




        if(Input::get('match_id') == Input::has('match_id')) {
            $data= $data->where('match_id', Input::get('match_id'));
        }

        if(Input::get('filter') == "pending") {

            $data = $data->where('status', '=',0);

        } elseif (Input::get('filter') == "approved") {

            $data = $data->where('status', '=',1);

        } elseif (Input::get('filter') == "rejected") {

            $data = $data->where('status', '=',2);
        }

        $data = $data->with('user')->get();

        return view('admin.reports.selfies', compact('data', 'matches'));
    }

}