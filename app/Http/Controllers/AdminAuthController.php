<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\AdminAuthService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuthController extends Controller
{
    use GeneralTrait;

    private $adminauthservice;  

    public function __construct(AdminAuthService $adminauthservice)
    {
        $this->adminauthservice = $adminauthservice;
    }

    public function register(RegisterRequest $request)
    {
        try {
            return $this->adminauthservice->register($request);

        } catch (\Exception $exp) {
            return $this->returnError(100, 'try again');
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            return $this->adminauthservice->login($request);

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
