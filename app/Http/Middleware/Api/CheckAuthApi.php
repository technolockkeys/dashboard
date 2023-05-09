<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

class CheckAuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth('api')->check())
            return response()->api_error('unauthorized', 401);
//        $user = auth('api')->user();
////        if (($request->bearerToken() != $user->auth_token) || $user->auth_token == null|| $user->auth_token == ""|| empty($user->auth_token )) {
////            return response()->api_error('unauthorized', 401);
////        }
        return $next($request);

    }
}
