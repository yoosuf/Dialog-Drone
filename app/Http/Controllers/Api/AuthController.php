<?php
namespace App\Http\Controllers\Api;

use Validator;
use App;
use Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ArimacDrone\Users\AuthenticationManager;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use ArimacDrone\Shared\Exceptions\DomainException;
use Facebook\Facebook;
use Config;
use Facebook\Exceptions\FacebookResponseException;
use ArimacDrone\Users\Entity\User;

class AuthController extends Controller
{
    protected $users;

    public function __construct(AuthenticationManager $users)
    {
        $this->users = $users;
    }

    public function registerFromFacebook()
    {
        try {
            
            $fb = new Facebook([
                'app_id' => Config::get('facebook.app_id'),
                'app_secret' => Config::get('facebook.secret'),
                'default_graph_version' => 'v2.5',
            ]);

            $response = $fb->get('/me?fields=picture,name,id', Input::get('token'));

            $user = User::where('login', $response->getGraphUser()->getId())->first();

            if (!$user) {
                $user_data = [
                    'name' => $response->getGraphUser()->getName(),
                    'login' => $response->getGraphUser()->getId(),
                    'password' => str_random(10),
                    'email' => $response->getGraphUser()->getId().'@facebook.com',
                    'profile_image' => $response->getGraphUser()->getPicture()->getUrl(),
                    'login_type' => 'facebook'
                ];
                $user  = $this->users->createUser($user_data);
            }
            
            $token = JWTAuth::fromUser($user);

            return compact('token');

        } catch (FacebookResponseException $e) {
            return response()->json(['errors' => ['Provided facebook login token is invalid.']], 422);
        }
        
    }

    public function registerFromGoogle()
    {
        $url  = 'https://www.googleapis.com/plus/v1/people/me?access_token=';
        $url .= Input::get('token');

        $opts  = [
            'http' => [
                'method' => 'GET',
                'header' => "Accept: application/json"
            ]
        ];

        $context = stream_context_create($opts);
        $res     = @file_get_contents($url, false, $context);

        if (empty($res))
            throw new DomainException('Failed to get details from google+.');

        $res  = json_decode($res);


        if ($res->get('error'))
            throw new DomainException('Provided google login token is invalid.');

        $user = User::where('login', $res->get('id'))->first();

        if (!$user) {
            $user_data = [
                'name' => $res->get('displayName'),
                'login' => $res->get('id'),
                'password' => str_random(10),
                'email' => $res->get('emails.0.value'),
                'profile_image' => str_replace('?sz=50', '', $res->get('image.url')),
                'login_type' => 'google'
            ];

            $user  = $this->users->createUser($user_data);
        }
        
        $token = JWTAuth::fromUser($user);

        return compact('token');
        
    }

    public function signUp(Request $request)
    {
        $data  = $request->all();
        $data['login_type'] = 'email';
        
        $user  = $this->users->createUser($data);
        $token = JWTAuth::fromUser($user);
        return compact('token');
    }

    public function getToken(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('login', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        
        // all good so return the token
        return response()->json(compact('token'));
    }

    public function mobilePasswordResetCode()
    {
        $this->users->initiateMobilePasswordReset(Input::get('mobile'));
        return ['success' => true];
    }

    public function doMobilePasswordReset()
    {
        $user  = $this->users->doMobilePasswordReset(Input::get('mobile'), Input::get('code'), Input::get('password'));
        $token = JWTAuth::fromUser($user);
        return compact('token');
    }
}