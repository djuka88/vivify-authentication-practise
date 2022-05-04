<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;


class CheckJwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // check if user is logged in and have JWT token

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json(['status' => 'Token is Expired']);
        }
        catch(TokenInvalidException $e){
            return response()->json(['status' => 'Token is Invalid']);
        }

        return $next($request);
    }
}
