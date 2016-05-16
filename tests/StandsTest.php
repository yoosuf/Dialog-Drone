<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StandsTest extends TestCase
{
    public function testGetInvalidStand()
    {
        $stadium_id = $this->createStadium();

        $this->get("/stadiums/{$stadium_id}/stands/2")
             ->seeStatusCode(404)
             ->seeJson(['errors' => ["No Stand found."]]);

    }

    public function testCreateStand()
    {
        $stadium_id = $this->createStadium();

        $stand_params = [
            "name" => "A",
            'type' => 'standard'
        ];

        $this->post("/stadiums/{$stadium_id}/stands", $stand_params)->seeJson([
                 'name' => 'A',
                 'type' => 'standard'
             ]);

        $this->seeInDatabase('stands', ['name' => 'A']);
    }

    public function testCreateStandEmptyData()
    {
        $stadium_id = $this->createStadium();

        $this->post("/stadiums/{$stadium_id}/stands")
            ->seeJson(['errors' => ['The name field is required.', 'The type field is required.']]);
    }

    public function testListStands()
    {
        //adding a stand with diff stadium id to check if it returns
        DB::table('stands')->insert([
            'name' => 'C',
            'stadium_id' => 3
        ]);

        $stadium_id = $this->createStadium();

        $stand_params = [
            "name" => "A",
            "type" => "standard"
        ];

        $this->post("/stadiums/{$stadium_id}/stands", $stand_params);
        $this->post("/stadiums/{$stadium_id}/stands", ['name' => 'B'] + $stand_params);

        $stands = $this->parseJson($this->call('GET', "/stadiums/{$stadium_id}/stands"));
        
        $this->assertEquals(2, count($stands->data));
    }

    public function testUpdateStand()
    {
        $stadium_id = $this->createStadium();

        $params = [
            "name" => "A",
            "type" => "standard",
            "lat" => 89.45454,
            "lng" => 78.56565,
            "altitude" => 10,
            "yaw_default" => 2,
            "yaw_start" => 20,
            "yaw_end" => 10,
            "gimbal_pitch" => 0
        ];

        $created  = $this->call('POST', "/stadiums/{$stadium_id}/stands", $params);
        $created = $this->parseJson($created);

        $this->assertEquals(2, $created->yaw_default);
        $this->assertEquals(20, $created->yaw_start);
        $this->assertEquals(10, $created->yaw_end);

        $this->post("/stadiums/{$stadium_id}/stands/{$created->id}", ['lat' => '89.454540', 'altitude' => 3, 'gimbal_pitch' => 2])
             ->seeJson(['lat' => 89.454540, 'altitude' => 3]);
    }

    public function testUpdateWithInvalidData()
    {
        $stadium_id = $this->createStadium();

        $params = [
            "name" => "A",
            "type" => "standard"
        ];

        $created = $this->call('POST', "/stadiums/{$stadium_id}/stands", $params);
        $id      = $this->parseJson($created)->id;

        $params = [
            "lat" => 'a',
            "lng" => 'sds',
            "alt" => '',
            "yaw_default" => 2,
            "gimbal_pitch" => 0,
            "type" => "sds"
        ];

        $this->post("/stadiums/{$stadium_id}/stands/{$id}", $params)
             ->seeStatusCode(422)
             ->seeJson(['errors' => ["The lat must be a number.","The lng must be a number.","The selected type is invalid."]]);
    }

    public function testGetStand()
    {
        $stadium_id = $this->createStadium();

        $stand_params = [
            "name" => "A",
            "lat" => 89.45454,
            "lng" => 78.56565,
            "altitude" => 10,
            "type" => "standard"
        ];

        $created = $this->parseJson($this->call('POST', "/stadiums/{$stadium_id}/stands", $stand_params));

        $this->get("/stadiums/{$stadium_id}/stands/{$created->id}")->seeJson([
            "name" => "A",
            "lat" => 89.45454,
            "lng" => 78.56565,
            "altitude" => 10,
            "type" => "standard"
        ]);
    }

    public function deleteStand()
    {
        $stadium_id = $this->createStadium();

        $stand_params = [
            "name" => "A",
            'type' => 'standard'
        ];

        $created = $this->call("POST", "/stadiums/{$stadium_id}/stands", $stand_params);

        $this->seeInDatabase('stands', ['name' => 'A']);

        $this->delete("/stadiums/{$stadium_id}/stands/{$created->id}", $stand_params)->seeJson([
                 'success' => true
             ]);

        $this->dontSeeInDatabase('stands', ['name' => 'A']);
    }

    private function createStadium()
    {
        $params = [
            "name" => "SCC Grounds"
        ];

        return $this->parseJson($this->call('POST', '/stadiums', $params))->id;
    }

}