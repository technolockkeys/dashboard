<?php

namespace App\Http\Middleware\Backend;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
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
        if (auth('admin')->check()  && auth('admin')->user()->status == 1 )
        {
            return $next($request);
        }
        if (auth('admin')->check()){
            auth('admin')->logout();
        }
        return  redirect()->route('backend.login');
    }
}
