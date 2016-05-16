<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StadiumsTest extends TestCase
{
    public function testGetInvalidStadium()
    {
        $this->get('/stadiums/1')
             ->seeStatusCode(404)
             ->seeJson(['errors' => ["No Stadium found."]]);
    }

    public function testCreateStadium()
    {
        $params = [
            "name" => "SCC Grounds"
        ];

        $this->post('/stadiums', $params)->seeJson([
                 'name' => 'SCC Grounds',
             ]);
        $this->seeInDatabase('stadiums', ['name' => 'SCC Grounds']);
    }

    public function testListStadiums()
    {
        $params = [
            "name" => "SCC Grounds"
        ];

        $this->post('/stadiums', ['name' => 'SCC Grounds']);
        $this->post('/stadiums', ['name' => 'SCC Grounds 2']);

        $st = $this->parseJson($this->call('GET', '/stadiums'));

        $this->assertEquals(2, count($st->data));
    }

    public function testCreateStadiumValidation()
    {
        $this->post('/stadiums', [])
            ->seeStatusCode(422)
            ->seeJson(['errors' => ['The name field is required.']]);
    }
}