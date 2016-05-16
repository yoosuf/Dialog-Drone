<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use AspectMock\Test as test;
use Mockery as m;
use ArimacDrone\Users\Entity\User;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function parseJson($response)
    {
        return json_decode($response->getContent());
    }

    public function authUser()
    {
        $u = new User;
        $u->id = 1;
        $u->login = 'sahanlak';

        JWTAuth::shouldReceive('getToken')
                ->andReturn('token')
                ->shouldReceive('authenticate')
                ->andReturn($u)
                ->shouldReceive('parseToken')
                ->andReturn(m::self())
                ->shouldReceive('setRequest')
                ->andReturn(m::self());

        return $u;
    }

    public function tearDown()
    {
        parent::tearDown();
        test::clean();
        m::close();
    }
}
