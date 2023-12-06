<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use App\Traits\ResponseTrait;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminAuth extends Middleware
{
    use GeneralTrait;

    protected function authenticate($request,array $guards )
    {
        try{


            if ($this->auth->guard('admin-api')->check()) {
                return $this->auth->shouldUse('admin-api');
            }


        $this->unauthenticated($request, ['admin-api']);
        }
        catch (TokenExpiredException $e){
              return  $this -> returnError('401','Unauthenticated user');
        }catch (JWTException $e)
        {
             return  $this -> returnError('', 'token_invalid '.$e->getMessage());
        }
    }

     /**
    * Get the path the user should be redirected to when they are not authenticated.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return string|null
    */

   protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            return route('login');
        }
    }
    }