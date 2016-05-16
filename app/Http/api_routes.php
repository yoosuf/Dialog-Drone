<?php
use ArimacDrone\Stadium\Entity\Stand;
use ArimacDrone\Stadium\Entity\Score;
use ArimacDrone\Stadium\Entity\Match;
use Carbon\Carbon;

$stadiums = App::make('ArimacDrone\Stadium\StadiumsManager');

Route::post('/auth/signup', 'Api\AuthController@signUp');
Route::post('/auth/facebook', 'Api\AuthController@registerFromFacebook');
Route::post('/auth/google', 'Api\AuthController@registerFromGoogle');
Route::post('/auth/signin_token', 'Api\AuthController@getToken');
Route::post('/auth/mobile_password_reset_code', 'Api\AuthController@mobilePasswordResetCode');
Route::post('/auth/mobile_password_reset', 'Api\AuthController@doMobilePasswordReset');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/me', 'Api\UserController@me');
    Route::post('/auth/request_mobile_validation', 'Api\UserController@requestMobileValidation');
    Route::post('/auth/validate_mobile', 'Api\UserController@validateMobile');

    Route::post('/matches/{id}/scores', 'Api\MatchController@submitScores');

    Route::get('/me', 'Api\UserController@me');
    Route::post('/me', 'Api\UserController@update');
    Route::post('/auth/request_mobile_validation', 'Api\UserController@requestMobileValidation');
    Route::post('/auth/validate_mobile', 'Api\UserController@validateMobile');
    Route::get('/coupon/{match_id}', 'Api\RewardController@getRewardsForMatch');
    Route::post('/redeem-coupon', 'Api\RewardController@redeemReward');
    Route::get('/coupon-list', 'Api\RewardController@getCouponList');
    Route::get('/ques/{match_id}', 'Api\QuestionController@getQuestions');
    Route::post('/ques-answer', 'Api\QuestionController@answerToQuestions');
    Route::get('/team-players/{match_id}/{team_id}', 'Api\TeamController@getTeamPlayers');
    Route::post('/vote-player', 'Api\TeamController@voteTeamPlayer');


});

Route::group(['middleware' => 'api_basic'], function () use ($stadiums) {


    /**
     * Selfies and Shoutouts should be able to add by users (we take user id in that case)
     * and non users
     */
    Route::post('/matches/{id}/selfies', 'Api\UserMediaController@submitSelfie');
    Route::post('/matches/{id}/shoutouts', 'Api\UserMediaController@submitShoutout');


    Route::get('/banner-images', 'Api\BannerImageController@getAllBannerImages');

    Route::get('/manage', function () use ($stadiums) {
        $stadiums_list = $stadiums->listStadiums();
        return view('dashboard', compact('stadiums_list'));
    });

    Route::get('/manage/scores/{id}', function ($id) {
        $stands = Stand::where('stadium_id', $id)->get();
        return view('scores', compact('stands'));
    });

    Route::get('/stadiums', function () use ($stadiums) {
        return ['data' => $stadiums->listStadiums()];
    });

    Route::post('/stadiums', function () use ($stadiums) {
        return $stadiums->createStadium(Input::get('name'));
    });

    Route::get('/stadiums/{id}', function ($id) use ($stadiums) {
        return $stadiums->getStadium($id);
    });

    Route::get('/stadiums/{id}/stands', function ($id) use ($stadiums) {
        $stadium = $stadiums->getStadiumManager($id);
        return ['data' => $stadium->getStands()];
    });


    Route::post('/stadiums/{id}/stands', function ($id) use ($stadiums) {
        $stadium = $stadiums->getStadiumManager($id);
        return $stadium->createStand(DTO::make(Input::get()));
    });


    Route::post('/stadiums/{id}/stands/{stand_id}', function ($id, $stand_id) use ($stadiums) {
        $stadium = $stadiums->getStadiumManager($id);
        return $stadium->updateStand($stand_id, DTO::make(Input::get()));
    });

    Route::get('/stadiums/{id}/stands/{stand_id}', function ($id, $stand_id) use ($stadiums) {
        $stadium = $stadiums->getStadiumManager($id);
        return $stadium->getStand($stand_id);
    });

    Route::delete('/stadiums/{id}/stands/{stand_id}', function ($id, $stand_id) use ($stadiums) {
        $stadium = $stadiums->getStadiumManager($id);
        $stadium->deleteStand($stand_id);
        return ['success' => true];
    });

    Route::post('/matches/{match_id}/finish_session', function ($match_id) use ($stadiums) {

        $match = Match::findOrFail($match_id);
        $stadium = $stadiums->getStadiumManager($match->stadium_id);

        $way = $stadium->getWayPointToBestStand($match_id);
        return $way->getData();
    });

    Route::post('/matches/{match_id}/start_session', function ($match_id) use ($stadiums) {

        $match = Match::findOrFail($match_id);
        $stadium = $stadiums->getStadiumManager($match->stadium_id);
        $way = $stadium->startSession($match_id)->getData();
        return $way;
    });


    Route::get('/matches/live_scores', function () {
        $query = Match::with('stadium', 'teams');
        $query->where("matches.status", 1);

        $res = [];

        foreach ($query->get() as $match) {
            $formatted = [
                'match_id' => $match->id,
                'sub_status' => $match->sub_status,
                'teams' => []
            ];

            foreach ($match->teams as $team) {
                $formatted['teams'][] = [
                    'team_id' => $team->id,
                    'score' => $team->score
                ];
            }

            $res[] = $formatted;
        }

        return $res;
    });

    Route::get('/matches/{match_id}', function ($match_id) {
        $match = Match::with('stadium', 'teams')
            ->where('matches.id', $match_id)
            ->where('matches.is_active', '1')
            ->first();

        if ($match) {
            foreach ($match->activation as $item) {
                $match['has_' . $item['type']] = $item['is_active'];
            }
            unset($match['activation']);
            return $match;
        }
        return \Response::json(['errors' => ['No Match found']], 404);
    });

    Route::get('/matches/{id}/activations/{type}', function ($match_id, $type) {
        $activation = DB::table('drone_controls')
            ->where('match_id', $match_id)
            ->where('type', $type)
            ->first();

        if (!$activation)
            return ['enabled' => false];

        if ($activation->force_start)
            return ['enabled' => true];

        try {

            $start_at = new Carbon($activation->start_at);
            $end_at = new Carbon($activation->end_at);

            if (Carbon::now()->between($start_at, $end_at))
                return ['enabled' => true];

        } catch (Exception $e) {

        }

        return ['enabled' => false];
    });

    Route::post('/matches/{id}/scores', function ($match_id) {

        if (Input::get('score') > 0) {
            Score::create([
                'stand_id' => Input::get('stand_id'),
                'match_id' => $match_id,
                'score' => (int)Input::get('score')
            ]);

        }

        return ['success' => true];
    });

    Route::get('/matches', function () {

        ValidationException::validate(Input::get(), [
            'from' => 'date'
        ]);

        $query = Match::with('stadium', 'teams')->where('is_active', 1);

        if (Input::get('from')) {
            $date = new Carbon(Input::get('from'));
            $query->where(DB::raw("DATE(scheduled)"), '>', $date->format('Y-m-d'));
        } else {
            $date = Carbon::now();
            $query->where(DB::raw("DATE(scheduled)"), $date->format('Y-m-d'));
        }

        $query->where('is_active', 1);
        //Commented by Shalinda
        /*
        if (Input::get('status') !== null)
            $query->where("status", Input::get('status'));
        */
        return $query->get();
    });
});