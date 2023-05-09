<?php

namespace App\Http\Middleware\Seller;

use Closure;
use Illuminate\Http\Request;

class IsNotLogined
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
        if (!(auth('seller')->check()) ){
            return $next($request);
        }
        return  redirect()->route('seller.home');
    }
}
