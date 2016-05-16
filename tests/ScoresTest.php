<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScoresTest extends TestCase
{
    public function testSendScores()
    {
        $this->post('/matches/1/scores', ['stand_id' => 1, 'score' => 10]);
        $this->post('/matches/2/scores', ['stand_id' => 2, 'score' => 2]);
        
        $this->seeInDatabase('scores', ['stand_id' => 1, 'match_id' => 1, 'score' => 10]);
        $this->seeInDatabase('scores', ['stand_id' => 2, 'match_id' => 2, 'score' => 2]);
    }

    public function testFinishSession()
    {
        $params = [
            "name" => 'A',
            "lat" => 89.45454,
            "lng" => 78.56565,
            "altitude" => 10,
            "yaw_default" => 2,
            "yaw_start" => 20,
            "yaw_end" => 20,
            "gimbal_pitch" => 0,
            "type" => "standard"
        ];

        $stadium_id = $this->createStadium();
        $created = $this->call('POST', "/stadiums/{$stadium_id}/stands", $params);
        $a_id    = $this->parseJson($created)->id;

        //create another stadium with scores
        $stadium2_id = $this->createStadium();
        $created     = $this->call('POST', "/stadiums/{$stadium2_id}/stands", $params);
        $b_id        = $this->parseJson($created)->id;

        //create match
        $match_id = DB::table('matches')->insertGetId([
            'stadium_id' => $stadium_id,
            'name' => 'Cricket',
            'scheduled' => new \DateTime,
            'status' => 1
        ]);

        $params = [
            "name" => 'A',
            "lat" => 60.45454,
            "lng" => -78.56565,
            "altitude" => 10,
            "yaw_default" => 2,
            "yaw_start" => 20,
            "yaw_end" => 20,
            "gimbal_pitch" => 0,
            "type" => "center"
        ];

        $this->call('POST', "/stadiums/{$stadium_id}/stands", $params);

        $user = $this->authUser();
        $params = [
            ['match_id' => $match_id, "stand_id" => $a_id, "score" => 10],
            ['match_id' => $match_id, "stand_id" => $a_id, "score" => 10],
            ['match_id' => $match_id, "stand_id" => $a_id, "score" => 10],
            ['match_id' => $match_id, "stand_id" => $a_id, "score" => 10],
            ['match_id' => $match_id, "stand_id" => $a_id, "score" => 10],
            ['match_id' => $match_id, "stand_id" => $b_id, "score" => 1],
        ];
        
        foreach ($params as $param)
            $this->call('POST', "/matches/{$param['match_id']}/scores", $param);
        

        $res    = $this->call('POST', "/matches/{$match_id}/finish_session");
        $parsed = $this->parseJson($res);

        $this->assertEquals('Go To Stand A', $parsed->mission->description);

        $start_assert = [
            "name" => "A",
            "latitude" => 60.4545400,
            "longitude" => -78.5656500,
            "altitude" => 10,
            "type" => "center"
        ];

        $this->assertEquals($start_assert, array_only((array) $parsed->mission->start, array_keys($start_assert)));

        $end_assert = [
            "name" => "A",
            "latitude" => 89.45454,
            "longitude" => 78.56565,
            "altitude" => 10,
            "type" => "standard"
        ];

        $this->assertEquals($end_assert, array_only((array) $parsed->mission->end, array_keys($start_assert)));
    }

    public function testFinishSessionWithoutScores()
    {
        $params = [
            "name" => 'A',
            "lat" => 89.45454,
            "lng" => 78.56565,
            "altitude" => 10,
            "yaw_default" => 2,
            "yaw_start" => 20,
            "yaw_end" => 20,
            "gimbal_pitch" => 0,
            "type" => "standard"
        ];

        $stadium_id = $this->createStadium();
        $created = $this->call('POST', "/stadiums/{$stadium_id}/stands", $params);
        $a_id    = $this->parseJson($created)->id;

        $match_id = DB::table('matches')->insertGetId([
            'stadium_id' => $stadium_id,
            'name' => 'Cricket',
            'scheduled' => new \DateTime,
            'status' => 1
        ]);

        $params = [
            "name" => 'A',
            "lat" => 60.45454,
            "lng" => -78.56565,
            "altitude" => 10,
            "yaw_default" => 2,
            "yaw_start" => 20,
            "yaw_end" => 20,
            "gimbal_pitch" => 0,
            "type" => "center"
        ];

        $this->call('POST', "/stadiums/{$stadium_id}/stands", $params);

        $this->post("/matches/{$match_id}/finish_session")
            ->seeStatusCode(422)
            ->seeJson(['errors' => ['No scores exist.']]);
    }

    public function testStartSession()
    {
        $params = [
            "name" => 'A',
            "lat" => 89.45454,
            "lng" => 78.56565,
            "altitude" => 10,
            "yaw_default" => 2,
            "yaw_start" => 20,
            "yaw_end" => 20,
            "gimbal_pitch" => 0,
            "type" => "center"
        ];

        $stadium_id = $this->createStadium();
        $created = $this->call('POST', "/stadiums/{$stadium_id}/stands", $params);
        $a_id    = $this->parseJson($created)->id;

        $another_id = $a_id + 1;

        //create match
        $match_id = DB::table('matches')->insertGetId([
            'stadium_id' => $stadium_id,
            'name' => 'Cricket',
            'scheduled' => new \DateTime,
            'status' => 1
        ]);

        $this->authUser();
        $this->post("/matches/{$match_id}/scores", ['stand_id' => $a_id, 'score' => 10]);
        $this->post("/matches/{$match_id}/scores", ['stand_id' => $a_id, 'score' => 20]);

        $this->seeInDatabase('scores', ['match_id' => $match_id, 'stand_id' => $a_id, 'score' => 10]);
        $this->seeInDatabase('scores', ['match_id' => $match_id, 'stand_id' => $a_id, 'score' => 20]);
        
        $res    = $this->call('POST', "/matches/{$match_id}/start_session");
        $parsed = $this->parseJson($res);

        $end_assert = [
            "name" => "A",
            "latitude" => 89.45454,
            "longitude" => 78.56565,
            "altitude" => 10,
            "type" => "center",
            "gimbal_pitch" => 0,
            "yaw_default" => 2,
            "yaw_start" => 20,
            "yaw_end" => 20,
        ];

        $this->assertEquals($end_assert, array_only((array) $parsed->mission->end, array_keys($end_assert)));
        $this->assertEquals('Go to ground center', $parsed->mission->description);
        $this->assertEquals('ground_center_nav', $parsed->mission->type);

        $this->assertEquals(2, \DB::table('scores')->whereIn('stand_id', [$a_id])->count());
    }

    private function createStadium()
    {
        $params = [
            "name" => "SCC Grounds"
        ];

        return $this->parseJson($this->call('POST', '/stadiums', $params))->id;
    }
}