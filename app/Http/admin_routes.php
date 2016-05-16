<?php
use  \App\Shoutout;
use Carbon\Carbon;
Route::get('/', function() {
    return redirect()->route('admin.dashboard');
});

Route::get('/big-screen', function() {

    $matches = \App\Match::
     where('scheduled', 'like', '%'.date("Y-m-d").'%')
                       -> lists('name', 'id');
    return view('admin.bigscreen',compact('matches'));
});

Route::get('/big-screen/wide', function() {

    $matches = \App\Match::where('scheduled', 'like', '%'.date("Y-m-d").'%')
        ->lists('name', 'id');
    return view('admin.bigscreen4x3',compact('matches'));
});

Route::post('/screen', function() {
    if (Request::input('access_code') == '@papare*987') {
        $type = Request::input('type');
        $matchID = Request::input('match_id');
        $view=null;
        if ($type == 'shout') {
            $view='admin.shoutouts.screen';
        }elseif($type == 'selfies') {
            $view='admin.selfies.screen';
        }elseif($type=='scores'){
            $view='admin.score.screen';
        }
        return view($view,compact('matchID'));
    }
        Session::flash('msg', "Invalid Access Code.");
        return Redirect::back();
});

Route::post('/screen/wide', function() {
    if (Request::input('access_code') == '@papare*987') {
        $type = Request::input('type');
        $matchID = Request::input('match_id');
        $view=null;
        if ($type == 'shout') {
            $view='admin.shoutouts.screen4x3';
        }elseif($type == 'selfies') {
            $view='admin.selfies.screen4x3';
        }elseif($type=='scores'){
            $view='admin.score.screen4x3';
        }
        return view($view,compact('matchID'));
    }
    Session::flash('msg', "Invalid Access Code.");
    return Redirect::back();
});



Route::get('/all-shouts/{match_id}', function($matchID){
    //Querrying all the unread shouts
    $unreadShouts = \App\Shoutout::select('message','emoji')
                            ->where('match_id',$matchID)
                            ->where('status',1)
                            ->where('is_read',0)
                            ->orderBy('id', 'DESC')
                            ->get();
    //Update shouts as already read
    \App\Shoutout::where('is_read', 0)
                ->where('match_id',$matchID)
                ->where('status', 1)
                ->update(['is_read' => 1]);

    return $unreadShouts;
});


Route::patch('/shouts-update/{match_id}', function($matchID){
    \App\Shoutout::where('is_read', 1)
        ->where('match_id',$matchID)
        ->where('status', 1)
        ->update(['is_read' => 0]);
});




Route::get('/stand-chart/{match_id}', function($matchID){

/*
    $shouts = DB::select('SELECT SUM(score) as tot_score,st.name
                          FROM scores sc
                          INNER JOIN stands st on sc.stand_id=st.id
                          WHERE match_id =?
                          GROUP BY stand_id',[$matchID]);
*/
    $scores = DB::select( DB::raw("SELECT s.id as 'stand_id' ,
                                  (SELECT  name FROM stands WHERE id = s.id) as 'name',
                                  (SELECT  type FROM stands WHERE id = s.id) as 'stand_type' ,
                                  (SELECT IFNULL(SUM(score),0)
                                   FROM `scores`
                                   WHERE `stand_id` = s.id and match_id = '$matchID') as 'tot_score'
                                   FROM stands s where stadium_id =
                                   (SELECT stadium_id
                                   from matches where id = '$matchID'
                                   )") );

    return json_encode($scores);
});



Route::get('/all-selfies/{match_id}', function($matchID){
    //Querrying all the unread selfies
            $unreadSelfies=  \App\Selfie::where('status',1)
                                    ->where('match_id',$matchID)
                                    ->where('is_read',0)
                                    ->orderBy('id', 'DESC')
                                    ->get();
    //Update selfies as already read
                            \App\Selfie::where('is_read',0)
                                ->where('match_id',$matchID)
                                ->where('status', 1)
                                ->update(['is_read' => 1]);
    return $unreadSelfies;
});

Route::patch('/selfies-update/{match_id}', function($matchID){
    \App\Selfie::where('is_read', 1)
        ->where('match_id',$matchID)
        ->where('status', 1)
        ->update(['is_read' => 0]);
});


Route::controllers([
    'secured' => 'Auth\AuthController',
]);


Route::group(
    [
        'middleware' => 'auth',
        'prefix' => 'admin',
        'namespace' => 'Admin'
    ],  function() {

    Route::get('/',
        [
            'as' => 'admin.dashboard',
            'uses' => 'DashboardControllers@index'
        ]
    );

    Route::resource('stadiums', 'StadiumsController', ['except' => ['show']]);
    Route::resource('stadiums.stands', 'StandsController', ['except' => ['show']]);
    Route::resource('matches', 'MatchesController', ['except' => ['show']]);
    Route::resource('push-notify', 'PushNotificationController', ['except' => ['show']]);

    Route::get('matches/{matchesId}/live',
        [
            'as' => 'admin.matches.live.index',
            'uses' => 'LiveController@index'
        ]
    );

    Route::patch('matches/{matchesId}/live',
        [
            'as' => 'admin.matches.live.update',
            'uses' => 'LiveController@update'
        ]
    );

    Route::get('matches/{matchesId}/score',
        [
            'as' => 'admin.matches.score.index',
            'uses' => 'ScoreController@index'
        ]
    );

    Route::post('matches/{matchesId}/score',
        [
            'as' => 'admin.matches.score.update',
            'uses' => 'ScoreController@update'
        ]
    );

    Route::post('matches/{matchesId}/clear/{standId}',
        [
            'as' => 'admin.matches.score.clear',
            'uses' => 'ScoreController@clear'
        ]
    );

    Route::resource('matches.teams', 'TeamsController', ['except' => ['create', 'show', 'edit']]);
    Route::resource('matches.shoutouts', 'ShoutoutController', ['except' => [ 'show']]);
    Route::resource('matches.selfies', 'SelfiesController', ['except' => [ 'show']]);
    Route::resource('matches.questions', 'QuestionsController', ['except' => [ 'show']]);
    Route::resource('matches.votes', 'VotesController', ['except' => ['show']]);
    Route::resource('matches.rewards', 'MatchRewardsController', ['except' => ['show']]);
    Route::resource('matches.activations', 'DroneActivationsController', ['except' => ['show']]);

    Route::post('matches/{matches}/activations/store',
        [
            'as' => 'match.drone.activation.form.update',
            'uses' => 'DroneActivationsController@store'
        ]
    );

    Route::resource('banners', 'BannerImagesController', ['except' => ['show', 'edit', 'update']]);
    Route::resource('help', 'HelpTipsController', ['except' => ['show']]);
    Route::resource('rewards', 'RewardsController', ['except' => ['show']]);

    Route::resource('users', 'UsersController', ['except' => ['show']]);

    Route::get('change-password', 'UsersController@getChangePassword');
    Route::post('update-password', 'UsersController@postUpdatePassword');


    Route::get('reports', ['as' => 'admin.reports.index', 'uses' => 'ReportsController@index' ]);
    Route::get('reports/users', ['as' => 'admin.reports.index', 'uses' => 'ReportsController@users']);
    Route::get('reports/shoutouts', ['as' => 'admin.reports.shoutouts', 'uses' => 'ReportsController@shoutouts']);
    Route::get('reports/selfies', ['as' => 'admin.reports.selfies', 'uses' => 'ReportsController@selfies']);
});
