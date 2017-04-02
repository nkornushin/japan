<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class MyGuest
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
        if (! \Session::has('userId')) {
                return $next($request);
        } else {
                return Redirect::route('index');
        }
        
    }
}
