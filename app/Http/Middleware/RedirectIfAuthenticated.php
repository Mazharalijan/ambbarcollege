<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check() && Auth::user()->hasRole('super-admin')) {
            //return redirect(RouteServiceProvider::HOME);
            return redirect()->intended(URL::route('admin.dashboard'));
        } elseif (Auth::guard($guard)->check() && Auth::user()->hasRole('sub-admin')) {
            return redirect()->intended(URL::route('admin.dashboard'));
        }

        return $next($request);
    }
}
