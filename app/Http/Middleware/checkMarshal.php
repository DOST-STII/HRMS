<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class checkMarshal
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
        if(Auth::user()->usertype == 'Marshal')
           return $next($request);
        return redirect('/');
    }
}
