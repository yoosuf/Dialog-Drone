<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use AspectMock\Test as test;

class AuthTest extends TestCase
{
    public function testSignup()
    {
        $data = [
            'name' => 'sahanlak',
            'login' => 'sahanlak',
            'email' => '',
            'password' => 'sahan123',
            'mobile' => '772754541',
            'profile_image' => ''
        ];

        $created = $this->call('POST', '/auth/signup', $data);
        $created = $this->parseJson($created);

        $this->seeInDatabase('users', ['login_type' => 'email', 'login' => 'sahanlak']);

        $this->assertNotNull($created->token);
    }

    public function testInvalidUserSignUp()
    {
        $this->post('/auth/signup', [])
                ->seeStatusCode(422);
    }

    public function testAuthFlow()
    {
        $login_data = [
            'login' => 'sahanlak',
            'password' => 'sahan123',
        ];

        $this->post('/auth/signin_token', $login_data)
            ->seeStatusCode(401);

        $signup_data = [
            'name' => 'sahanlak',
            'login' => 'sahanlak',
            'email' => '',
            'password' => 'sahan123',
            'mobile' => '772754541',
            'profile_image' => ''
        ];

        $token = $this->call('POST', '/auth/signup', $signup_data);
        $token = $this->parseJson($token);

        //initiate mobile validation
        $code = $this->call('POST', "/auth/request_mobile_validation?token={$token->token}");
        $code = $this->parseJson($code);
        
        $this->assertTrue($code->code > 1000);

        $this->post("/auth/validate_mobile?token={$token->token}")
            ->seeStatusCode(422)
            ->seeJson(['errors' => ['The provided verification code is invalid.']]);

        //validate mobile code
        $this->post("/auth/validate_mobile?token={$token->token}", ['code' => $code->code])
            ->seeJson(['success' => true]);

        //signin
        $created = $this->call('POST', '/auth/signin_token', $login_data);
        $created = $this->parseJson($created);
        $this->assertNotNull($token->token);

        //and see if user is active
        $user = $this->call('GET', "/me?token={$token->token}");
        $user = $this->parseJson($user);

        $this->assertSame('sahanlak', $user->login);
        $this->assertSame(true, $user->is_activated);
    }

    public function testInactiveUser()
    {
        $signup_data = [
            'name' => 'sahanlak',
            'login' => 'sahanlak',
            'email' => '',
            'password' => 'sahan123',
            'mobile' => '772754541',
            'profile_image' => ''
        ];


    }

    public function testUpdateUser()
    {
        $signup_data = [
            'name' => 'sahanlak',
            'login' => 'sahanlak',
            'email' => '',
            'password' => 'sahan123',
            'mobile' => '772754541',
            'profile_image' => ''
        ];

        $token = $this->call('POST', '/auth/signup', $signup_data);
        $token = $this->parseJson($token);

        $user = $this->call('GET', "/me?token={$token->token}");
        $user = $this->parseJson($user);

        $this->assertEquals('sahanlak', $user->login);

        $data = [
            'email' => 'sahan@arimaclanka.com',
        ];

        $user = $this->call('POST', "/me?token={$token->token}", $data);
        $user = $this->parseJson($user);

        $this->assertEquals('sahan@arimaclanka.com', $user->email);
    }

    public function testMobilePasswordResetCode()
    {
        $this->post('/auth/mobile_password_reset_code', ['mobile' => '0773456761'])
             ->seeJson(['errors' => ['No User found.']]);

        $signup_data = [
            'name' => 'sahanlak',
            'login' => 'sahanlak',
            'email' => '',
            'password' => 'sahan123',
            'mobile' => '772754541',
            'profile_image' => ''
        ];

        $token = $this->call('POST', '/auth/signup', $signup_data);

        test::func('ArimacDrone\Users', 'rand', 2345);

        $this->post('/auth/mobile_password_reset_code', ['mobile' => '772754541'])
             ->seeJson(['success' => true]);

        $this->seeInDatabase('mobile_password_resets', ['mobile' => '772754541', 'code' => 2345]);
        
        $this->post('/auth/mobile_password_reset', ['mobile' => '772754541'])
             ->seeJson(['errors' => ['Password reset code is invalid or no password reset requests found.']]);
        
        $data  = ['mobile' => '772754541', 'code' => 2345, 'password' => 'sahanz'];
        $token = $this->parseJson($this->call('POST', '/auth/mobile_password_reset', $data));

        $this->assertNotNull($token->token);

        $login_data = [
            'login' => 'sahanlak',
            'password' => 'sahanz',
        ];

        $token = $this->parseJson($this->call('POST', '/auth/signin_token', $login_data));
        $this->assertNotNull($token->token);
    }
}