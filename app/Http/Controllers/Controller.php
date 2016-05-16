<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Exceptions\ValidationException;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs;

    public function validate(Request $request, array $rules)
    {
        ValidationException::validate($request->all(), $rules);
    }
}
