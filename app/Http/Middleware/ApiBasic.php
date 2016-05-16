<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class ApiBasic
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // if ($request->header('X-Auth') != '14a9f8c6f825091c7ca23da3bce1dfd8')
        //     return response()->json(['errors' => 'invalid_api_key'], 401);

        return $next($request);
    }
}
