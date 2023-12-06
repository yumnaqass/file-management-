<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\AuthService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use GeneralTrait;

    private $authservice;

    public function __construct(AuthService $authService)
    {
        $this->authservice = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            return $this->authservice->register($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            return $this->authservice->login($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function Logout(Request $request)
    {
        $token = $request->header('auth-token');
        if ($token) {
            try {
                JWTAuth::logout();
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                $this->returnError(403, 'Somthing went wrong');
            }
            return $this->returnSuccessMessage('Logged out successfully ');
        }
        else {
            $this->returnError(403, 'Somthing went wrong');
        }

    }

}
