<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function authenticate(LoginRequest $request)
    {
        $user = $request->authenticate();

        return $user->createToken($request->input("device_name"));
    }
}
