<?php

namespace App\Http\Services;

use App\Http\Repositories\AdminAuthRepository;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuthService
{

    use GeneralTrait;

    private $adminauthrepo ;

    public function __construct(AdminAuthRepository $adminauth){

        $this->adminauthrepo = $adminauth;
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = $this->adminauthrepo->register($validated);
        return $this->returnSuccessMessage('register successfully');
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = $this->adminauthrepo->login($validated);

        // Check Password
        if (!$user || !Hash::check($request->all()['password'],$user->password)) {
            return $this->returnError(403, __(" The password does not match. Please try again. "));
        }

        $user->token = $this->generateToken($validated);
        return $this->returnData('Data', $user, 'login successfully');
    }

    public function generateToken(array $data){

        try {
            if (! $token = Auth::guard('admin-api')->attempt($data)) {
                return $this->returnError(403, 'wrong information');
            }
        } catch (JWTException $e) {
            return $this->returnError(403, 'Somthing went wrong. Please try again');
        }

        return $token;
    }


}