<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    public function testPublishSelfie()
    {
        //without auth
        $this->post('/matches/1/selfies', ['image' => 'http://example.com/sample.png', 'device_id' => 1])
            ->seeJson(['success' => true]);

        $this->seeInDatabase('user_selfies', [
            'user_id' => 0,
            'match_id' => 1,
            'image' => 'http://example.com/sample.png',
            'status' => 0
        ]);

        //with auth
        $this->authUser();

        $this->post('/matches/1/selfies', ['image' => 'http://example.com/sample.png', 'device_id' => 1])
            ->seeJson(['success' => true]);

        $this->seeInDatabase('user_selfies', [
            'user_id' => 1,
            'match_id' => 1,
            'image' => 'http://example.com/sample.png',
            'status' => 0
        ]);
    }

    public function testPublishSelfieValidation()
    {
        $this->authUser();

        $this->post('/matches/1/selfies')
            ->seeStatusCode(422)
            ->seeJson(['errors' => ['The image field is required.', 'The device id field is required.']]);

        $this->post('/matches/1/selfies', ['image' => 'ss'])
            ->seeStatusCode(422)
            ->seeJson(['errors' => ['The image format is invalid.', 'The device id field is required.']]);
    }

    public function testPublishShoutout()
    {
        //without auth user
        $this->post('/matches/1/shoutouts', ['message' => 'hello', 'device_id' => 1])
            ->seeJson(['success' => true]);

        $this->seeInDatabase('user_shoutouts', ['user_id' => 0, 'match_id' => 1, 'message' => 'hello', 'status' => 0]);

        //with auth user
        $this->authUser();

        $this->post('/matches/1/shoutouts', ['message' => 'hello', 'device_id' => 1])
            ->seeJson(['success' => true]);

        $this->seeInDatabase('user_shoutouts', ['user_id' => 1, 'match_id' => 1, 'message' => 'hello', 'status' => 0]);
    }

    public function testPublishShoutoutValidation()
    {
        $this->authUser();

        $this->post('/matches/1/shoutouts')
            ->seeStatusCode(422)
            ->seeJson(['errors' => ['The message field is required.', 'The device id field is required.']]);
    }
}