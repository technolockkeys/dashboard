<?php

namespace App\Http\Middleware\Backend;

use Closure;
use Illuminate\Http\Request;

class IsNotlogined
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
        if (!(auth('admin')->check()) ){
            return $next($request);
        }
        return  redirect()->route('backend.home');
    }
}
