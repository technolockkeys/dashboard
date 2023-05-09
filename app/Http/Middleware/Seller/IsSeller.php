<?php

namespace App\Http\Middleware\Seller;

use Closure;
use Illuminate\Http\Request;

class IsSeller
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

        if  (auth('seller')->check()  && auth('seller')->user()->status == 1  )
        {
            return $next($request);
        }
        if (auth('seller')->check()){
            auth('seller')->logout();
        }
        return  redirect()->route('seller.login');
    }
}
