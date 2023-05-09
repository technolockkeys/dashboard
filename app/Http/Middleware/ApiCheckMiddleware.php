<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiCheckMiddleware
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
        if($request->header('secret-key') ==   config('app.api_secret') && $request->header('api-key') == config('app.api_key') ){
            return $next($request);
        }else{
            return response()->api_error(trans('api.you_dont_have_permission_to_use_this_api'),403,null,true);
        }
    }
}
