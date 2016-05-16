<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MatchTest extends TestCase
{

    public function testDroneControlActivations()
    {
        DB::table('drone_controls')
            ->insert([
                [
                    'match_id' => 1,
                    'type' => 'drone',
                    'start_at' => new Carbon('-3 minutes'),
                    'end_at' => new Carbon('+3 minutes'),
                    'force_start' => 0
                ],
                [
                    'match_id' => 1,
                    'type' => 'selfie',
                    'start_at' => new Carbon('yesterday'),
                    'end_at' => new Carbon('yesterday'),
                    'force_start' => 1
                ],
                [
                    'match_id' => 1,
                    'type' => 'quiz',
                    'start_at' => new Carbon('yesterday'),
                    'end_at' => new Carbon('yesterday'),
                    'force_start' => 0
                ]
            ]);

        $this->get('/matches/1/activations/drone')
            ->seeJson(['enabled' => true]);

        $this->get('/matches/1/activations/selfie')
            ->seeJson(['enabled' => true]);

        $this->get('/matches/1/activations/quiz')
            ->seeJson(['enabled' => false]);

        $this->get('/matches/1/activations/shoutout')
            ->seeJson(['enabled' => false]);
    }

    public function testLiveMatchScores()
    {
        DB::table('matches')
            ->insert([
                'id' => 1,
                'sub_status' => 'halftime',
                'status' => 1
            ]);

        DB::table('match_teams')
            ->insert([
                [
                    'id' => 1,
                    'match_id' => 1,
                    'score' => 20
                ],
                [
                    'id' => 2,
                    'match_id' => 1,
                    'score' => 10
                ]
            ]);

        $parsed = $this->parseJson($this->call('GET', '/matches/live_scores'));
        $assert = [
            'match_id' => 1,
            'sub_status' => 'halftime',
            'teams' => [
                [
                    'team_id' => 1,
                    'score'   => 20
                ],
                [
                    'team_id' => 2,
                    'score'   => 10
                ]
            ]
        ];

        $this->assertEquals($parsed[0], json_decode(json_encode($assert)));
    }

}