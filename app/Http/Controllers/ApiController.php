<?php
namespace App\Http\Controllers;

use JWTAuth;
use AuthenticationManager;

abstract class ApiController extends Controller
{
    /**
     * Authenticated user
     * @var [type]
     */
    protected $user;

    /**
     * AuthManager
     * @var [type]
     */
    protected $auth;

    public function __construct(AuthenticationManager $auth)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->auth = $auth;
    }
}