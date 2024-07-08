<?php

namespace App\Http\Middleware;

use Closure;

class FrontendMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(
            auth()->user()->hasRole('student')
        ){
            return $next($request);
        } else {
            return abort(403,'This action is unauthorized.');
        }
    }
}
